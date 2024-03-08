<?php

declare(strict_types=1);

namespace App\Service;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use App\Models\Project as ProjectModel;
use App\Models\ProjectFile;

class ProjectService
{
    const STORAGE_PATH = 'app/project_files';
    /**
     * @var ProjectModel|null
     */
    private ?ProjectModel $project = null;

    /**
     * @param DBParser $dbParser
     */
    public function __construct(
        private readonly DBParser $dbParser,
        private readonly FileProjectService $fileProjectService
    ){
    }

    /**
     * @param UploadedFile $file
     * @return ProjectModel
     */
    public function create(UploadedFile $file)
    {
        $filePath = $file->path();
        $tables = $this->dbParser->parse($filePath);

        $project = $this->createProject($file->getClientOriginalName());

        $this->project = $project;

        $projectDirectoryPath = self::STORAGE_PATH . DIRECTORY_SEPARATOR . $project->id;
        $this->fileProjectService->createDirectoryIfNotExists($projectDirectoryPath);

        foreach ($tables as $table => $columns) {
            $this->createCsvFile($table, $columns);
            $this->assignFile($project, $table);
        }

        return $project;
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @return void
     */
    private function createCsvFile(string $tableName, array $columns): void
    {
        $csvFilePath = storage_path(self::STORAGE_PATH . DIRECTORY_SEPARATOR . $this->project->id . DIRECTORY_SEPARATOR . $tableName);
        $csv = new CSV($csvFilePath);
        $csv->create($columns);
    }

    /**
     * @param ProjectModel $project
     * @param string $fileName
     * @return ProjectFile
     */
    public function assignFile(ProjectModel $project, string $fileName): ProjectFile
    {
        $projectFile = new ProjectFile();
        $projectFile->project_id = $project->id;
        $projectFile->file_path = $fileName;
        $projectFile->save();
        return $projectFile;
    }

    /**
     * @return ProjectModel[]
     */
    public function getAll(): Collection
    {
        return ProjectModel::orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param int $id
     * @return ProjectModel|null
     */
    public function get(int $id): ?ProjectModel
    {
        return ProjectModel::with('files')->find($id)?->getModel();
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        /** @var ProjectModel $project */
        $project = ProjectModel::find($id);
        $project->delete();
    }

    /**
     * @param array $ids
     * @return ProjectModel
     */
    public function merge(array $ids): ProjectModel
    {
        $projectsBuilder = ProjectModel::with('files')->whereIn('id', $ids);
        $projects = $projectsBuilder->get();

        $name = $this->getName($projects);
        $mergedProject = $this->createProject($name);

        $projectDirectoryPath = self::STORAGE_PATH . DIRECTORY_SEPARATOR . $mergedProject->id;
        $this->fileProjectService->createDirectoryIfNotExists($projectDirectoryPath);

        $files = $this->fileProjectService->mergeProjects($projects, $mergedProject);

        foreach ($files as $fileName)
        {
            $this->assignFile($mergedProject, $fileName);
        }

        $projectsBuilder->delete();

        return $mergedProject;
    }

    /**
     * @param string $name
     * @return ProjectModel
     */
    public function createProject(string $name): ProjectModel
    {
        $project = new ProjectModel();
        $project->name = $name;
        $project->save();

        return $project;
    }

    /**
     * @param Collection $projects
     * @return string
     */
    private function getName(Collection $projects): string
    {
        return substr('Merged ' .
            implode(',', array_map(fn ($item) => $item['name'], $projects->toArray())),
            0,
            255
        );
    }
}
