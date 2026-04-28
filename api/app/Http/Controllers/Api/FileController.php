<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(
        protected FileService $fileService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'nullable|string|max:100',
            'attachable_type' => 'nullable|in:user,product',
            'attachable_id' => 'nullable|integer|min:1|required_with:attachable_type',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        try {
            $files = $this->fileService->listForUser($request->user(), $validated);
        } catch (AuthorizationException $exception) {
            return $this->forbidden($exception->getMessage());
        } catch (ModelNotFoundException) {
            return $this->notFound('Attachable resource not found.');
        }

        return $this->success($files, 'Files retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240',
            'type' => 'required|string|max:100',
            'directory' => 'nullable|string|max:100',
            'attachable_type' => 'nullable|in:user,product',
            'attachable_id' => 'nullable|integer|min:1|required_with:attachable_type',
            'replace_existing' => 'nullable|in:true,false,1,0,on,off,yes,no',
        ]);

        try {
            $file = $this->fileService->upload($request->user(), $validated['file'], $validated);
        } catch (AuthorizationException $exception) {
            return $this->forbidden($exception->getMessage());
        } catch (ModelNotFoundException) {
            return $this->notFound('Attachable resource not found.');
        }

        return $this->success($file, 'File uploaded successfully', 201);
    }
}
