<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\ProjectFile;
use App\Service\ProjectService;
use App\Models\Project;
use ZipArchive;

class FileProjectService
{
    /**
     * @param Project $project
     * @return string
     */
    public function compressToArchiveProject(Project $project): string
    {
        $rootPath = storage_path(ProjectService::STORAGE_PATH . DIRECTORY_SEPARATOR . $project->id);

        $zip = new ZipArchive();
        $zipName = $project->name . '.zip';

        if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)
        {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($rootPath));
            foreach ($files as $file)
            {
                if (!$file->isDir())
                {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        return $zipName;
    }

    /**
     * @param ProjectFile $projectFile
     * @return string
     */
    public function downloadProjectFile(ProjectFile $projectFile): string
    {
        $filePath = storage_path(
            ProjectService::STORAGE_PATH .
            DIRECTORY_SEPARATOR .
            $projectFile->project_id .
            DIRECTORY_SEPARATOR .
            $projectFile->file_path
        );

        if (!file_exists($filePath)) {
            abort(404);
        }

        return $filePath;
    }

    /**
     * @param string $directoryPath
     * @return void
     */
    public function createDirectoryIfNotExists(string $directoryPath): void
    {
        $fullPath = storage_path($directoryPath);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
    }

    /**
     * @param $projects
     * @param Project $mergedProject
     * @return string[]
     */
    public function mergeProjects($projects, Project $mergedProject): array
    {
        $files = [];
        foreach ($projects as $project) {
            foreach ($project->files as $file) {
                $fileName = $file->file_path;
                $files[$fileName] = $fileName;
                $existFilePath = storage_path(ProjectService::STORAGE_PATH . DIRECTORY_SEPARATOR . $project->id . DIRECTORY_SEPARATOR . $fileName);
                $mergedFilePath = storage_path(ProjectService::STORAGE_PATH . DIRECTORY_SEPARATOR . $mergedProject->id . DIRECTORY_SEPARATOR . $fileName);

                $existFile = fopen($existFilePath, 'r');
                $mergedFile = fopen($mergedFilePath, 'a');

                while (!feof($existFile)) {
                    $content = fread($existFile, 8192);
                    fwrite($mergedFile, $content);
                }

                fclose($existFile);
                fclose($mergedFile);
            }
        }

        return $files;
    }

    /**
     * @param Project $project
     * @return void
     */
    public static function removeProjectDir(Project $project): void
    {
        $dir = storage_path(ProjectService::STORAGE_PATH . DIRECTORY_SEPARATOR . $project->id);
        rmdir($dir);
    }
}
