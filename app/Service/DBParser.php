<?php

declare(strict_types=1);

namespace App\Service;

use PHPSQLParser\PHPSQLParser;

class DBParser
{
    // const REGEX_PATTERN = '/INSERT INTO +`(.*?)` \((.*?)\).*?VALUES(.*?(?=\);)\))/s';
    const REGEX_PATTERN = '/INSERT INTO +`.*?` \(.*?\).*?VALUES.*?(?=\);)\)/s';
    const READ_LENGTH = 8192;

    /**
     * @param string $filePath
     * @return array
     */
    public function parse(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        $buffer = '';
        $startPos = null;

        $result = [];

        while (!feof($handle)) {
            $startPos = $this->resetStartPosition($handle, $startPos);
            $buffer .= fread($handle, self::READ_LENGTH);

            $hasMatch = preg_match_all(self::REGEX_PATTERN, $buffer, $matches, PREG_OFFSET_CAPTURE);

            if ($hasMatch) {
                $result = array_merge_recursive($result, $this->generateAssociativeResult($matches));
                $lastElement = end($matches[0]);
                $currentPos = ftell($handle);

                $startPos = $currentPos - (strlen($buffer) - ($lastElement[1] + strlen($lastElement[0])));
                $buffer = '';
            }
        }

        fclose($handle);

        return $result;
    }

    /**
     * @param $handle
     * @param $startPos
     * @return int|null
     */
    private function resetStartPosition($handle, $startPos): ?int
    {
        if ($startPos !== null) {
            fseek($handle, $startPos);
            $startPos = null;
        }
        return $startPos;
    }

    /**
     * @param array $matches
     * @return array
     */
    private function generateAssociativeResult(array $matches): array
    {
        $result = [];

        foreach ($matches[0] as $group){
            list($tableName, $values) = $this->parseMatchGroup($group);
            $result[$tableName] = $values;
        }

        return $result;
    }

    /**
     * @param $group
     * @return array
     */
    private function parseMatchGroup($group): array
    {
        $sql = strip_tags(htmlspecialchars_decode($group[0]));
        $parser = new PHPSQLParser($sql);
        $parsed = $parser->parsed;
        $tableName = str_replace('`', '', $this->getTable($parsed['INSERT']));
        $values = array_map(fn ($item) => array_map(fn ($data) => $data['base_expr'], $item['data']), $parsed['VALUES']);
        return [$tableName, $values];
    }

    /**
     * @param array $inserts
     * @return string
     */
    private function getTable(array $inserts): string
    {
        foreach ($inserts as $insert) {
            if (empty($insert['table'])) {
                continue;
            }

            return $insert['table'];
        }

        return '';
    }
}
