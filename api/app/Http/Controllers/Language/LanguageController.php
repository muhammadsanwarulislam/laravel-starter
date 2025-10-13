<?php
declare(strict_types=1);
namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\CreateOrUpdateRequest;
use App\Services\Language\LanguageService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    use JsonResponseTrait;

    public function __construct(protected LanguageService $languageService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $this->languageService->getLanguages($request->all());

            return $this->successJsonResponse('Language list', $data['languages']['result']);
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrUpdateRequest $request)
    {
        try {
            $data = $this->languageService->createLanguage($request->all());

            return $this->successJsonResponse('Language created successfully', $data);
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = $this->languageService->getLanguageById($id);

            return $this->successJsonResponse('Language details', $data);
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateOrUpdateRequest $request, string $id)
    {
        try {
            $data = $this->languageService->updateLanguage($request->all(), $id);

            return $this->successJsonResponse('Language updated successfully', $data);
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
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
