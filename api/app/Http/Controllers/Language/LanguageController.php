<?php
declare(strict_types=1);
namespace App\Http\Controllers\Language;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Services\Language\LanguageService;
use App\Http\Requests\Language\CreateOrUpdateRequest;

class LanguageController extends Controller
{
    use JsonResponseTrait;

    public function __construct(protected LanguageService $languageService)
    {
    }

    public function index(Request $request)
    {
        try {
            $data = $this->languageService->getLanguages($request->all());

            return $this->successJsonResponse('Language list', [
                'languages'     => LanguageResource::collection($data['languages']),
                'pagination'    => $data['pagination'],
                'metadata'      => $data['metadata']
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function store(CreateOrUpdateRequest $request)
    {
        try {
            $data = $this->languageService->createLanguage($request->validated());
            return $this->successJsonResponse('Language created successfully', $data);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $data = $this->languageService->getLanguageById($id);
            return $this->successJsonResponse('Language details', new LanguageResource($data));
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function update(CreateOrUpdateRequest $request, string $id)
    {
        try {
            $data = $this->languageService->updateLanguage($request->validated(), $id);
            return $this->successJsonResponse('Language updated successfully', $data);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->languageService->deleteLanguageById($id);
            return $this->successJsonResponse('Language deleted successfully');
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }
}