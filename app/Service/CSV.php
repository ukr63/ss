<?php

declare(strict_types=1);

namespace App\Service;

class CSV
{
    private $file;

    public function __construct(string $filePath)
    {
        $this->file = $this->openFile($filePath);
    }

    public function create(array $data): void
    {
        foreach ($data as $row) {
            if (sizeof($row) > 100) {
                continue;
            }
            fputcsv($this->file, $row);
        }
    }

    /**
     * @param string $filePath
     * @return false|resource
     */
    private function openFile(string $filePath)
    {
        return fopen($filePath, 'w');
    }

    public function __destruct()
    {
        fclose($this->file);
    }
}
