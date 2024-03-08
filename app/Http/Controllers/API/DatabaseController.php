<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Models\ProjectFile;
use App\Service\FileProjectService;
use Exception;
use App\Http\Requests\Api\ProjectRequest;
use App\Service\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class DatabaseController extends Controller
{
    /**
     * @param ProjectService $projectService
     * @param FileProjectService $downloadProjectService
     */
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly FileProjectService $downloadProjectService
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $project = $this->projectService->create($request->file('file'));
            DB::commit();
            return response()->json($project);
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->getAll();
        return response()->json($projects);
    }

    /**
     * @param ProjectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function detail(ProjectRequest $request, int $id): JsonResponse
    {
        return response()->json(
            $this->projectService->get($id)
        );
    }

    /**
     * @param ProjectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(ProjectRequest $request, int $id): JsonResponse
    {
        $this->projectService->delete($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function merge(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $project = $this->projectService->merge($request->get('ids'));
            DB::commit();
            return response()->json($project);
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param ProjectRequest $request
     * @param int $id
     * @return BinaryFileResponse
     */
    public function downloadProject(ProjectRequest $request, int $id): BinaryFileResponse
    {
        $project = $this->projectService->get($id);
        $zipName = $this->downloadProjectService->compressToArchiveProject($project);
        return response()->download($zipName);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return BinaryFileResponse
     */
    public function downloadProjectFile(Request $request, int $id): BinaryFileResponse
    {
        $file = ProjectFile::find($id);
        $filePath = $this->downloadProjectService->downloadProjectFile($file);
        return response()->download($filePath);
    }
}
