<?php
declare(strict_types=1);
namespace App\Http\Controllers\FileManager;

use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileManager\FileManagerRequest;
use App\Http\Resources\FileManager\FileManagerResource;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('limit', 10);
            $page = $request->get('offset', 1);

            $query = FileManager::with(['user']);

            // Search functionality
            if ($request->has('option') && $request->get('option') === 'search') {
                $searchData = $request->get('searchData');
                $searchFields = explode(',', $request->get('searchFields', 'name,type'));

                $query->where(function ($q) use ($searchData, $searchFields) {
                    foreach ($searchFields as $field) {
                        $q->orWhere($field, 'like', "%{$searchData}%");
                    }
                    // Search in user relation
                    $q->orWhereHas('user', function ($userQuery) use ($searchData) {
                        $userQuery->where('name', 'like', "%{$searchData}%")
                            ->orWhere('email', 'like', "%{$searchData}%");
                    });
                });
            }

            // Filter by type
            if ($request->has('type') && $request->get('type') !== 'all') {
                $query->where('type', $request->get('type'));
            }

            // Filter by user
            if ($request->has('user_id')) {
                $query->where('user_id', $request->get('user_id'));
            }

            $files = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'files' => FileManagerResource::collection($files),
                    'pagination' => [
                        'total' => $files->total(),
                        'per_page' => $files->perPage(),
                        'current_page' => $files->currentPage(),
                        'last_page' => $files->lastPage(),
                        'from' => $files->firstItem(),
                        'to' => $files->lastItem(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch files',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileManagerRequest $request): JsonResponse
    {
        try {
            $file = $request->file('file');

            // Generate UUID
            $uuid = \Illuminate\Support\Str::uuid();

            // Store file
            $path = $file->store('files/' . date('Y/m'), 'public');

            // Create file record
            $fileManager = FileManager::create([
                'user_id' => $request->user_id,
                'uuid' => $uuid,
                'name' => $request->name ?? $file->getClientOriginalName(),
                'file' => $file->getClientOriginalName(),
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => new FileManagerResource($fileManager->load('user'))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FileManager $fileManager): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new FileManagerResource($fileManager->load('user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch file',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Download the specified file.
     */
    public function download(FileManager $fileManager): JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        try {
            if (!Storage::disk('public')->exists($fileManager->path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            return Storage::disk('public')->download($fileManager->path, $fileManager->file);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download file',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileManager $fileManager): JsonResponse
    {
        try {
            // Delete physical file
            if (Storage::disk('public')->exists($fileManager->path)) {
                Storage::disk('public')->delete($fileManager->path);
            }

            // Delete record
            $fileManager->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get files by user ID
     */
    public function getByUser($userId): JsonResponse
    {
        try {
            $files = FileManager::with('user')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => FileManagerResource::collection($files)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user files',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
