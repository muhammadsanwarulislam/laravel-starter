<?php
declare(strict_types=1);
namespace App\Http\Controllers\Localization;

use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Repository\Localization\LocalizationRepository;

class LocalizationController extends Controller
{
    use JsonResponseTrait;
    private $localizationRepo;

    public function __construct(LocalizationRepository $localizationRepo)
    {
        $this->localizationRepo = $localizationRepo;
    }

    /**
     * Get translations for specific locale
     */
    public function index($locale): JsonResponse
    {
        try {
            $translations = $this->localizationRepo->getTranslationsByLocale($locale);

            if (empty($translations)) {
                return $this->successJsonResponse('No translations found for the specified locale', []);
            }

            return $this->successJsonResponse('Translations fetched successfully', $translations);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Create a new translation
     */
    public function store(Request $request, $locale): JsonResponse
    {
        try {
            // Validate the request
            $request->validate([
                'key' => 'required|string|max:255',
                'value' => 'required|string',
            ]);

            // Create a new translation
            $translation = $this->localizationRepo->createOrUpdateTranslation(
                $locale,
                $request->input('key'),
                $request->input('value')
            );

            if (!$translation) {
                return $this->errorJsonResponse('Language not found or translation could not be created');
            }

            return $this->successJsonResponse('Translation created successfully', $translation);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Update an existing translation
     */
    public function update(Request $request, $locale): JsonResponse
    {
        try {
            // Validate the request
            $request->validate([
                'key' => 'required|string|max:255',
                'value' => 'required|string',
            ]);

            // Update the translation
            $translation = $this->localizationRepo->createOrUpdateTranslation(
                $locale,
                $request->input('key'),
                $request->input('value')
            );

            if (!$translation) {
                return $this->errorJsonResponse('Translation not found or could not be updated');
            }

            return $this->successJsonResponse('Translation updated successfully', $translation);
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Delete a translation by key
     */
    public function destroy(Request $request, $locale): JsonResponse
    {
        try {
            $request->validate([
                'key' => 'required|string|max:255',
            ]);

            $deleted = $this->localizationRepo->deleteTranslationByKey(
                $locale,
                $request->input('key')
            );

            if (!$deleted) {
                return $this->errorJsonResponse('Translation not found or could not be deleted');
            }

            return $this->successJsonResponse('Translation deleted successfully');
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Bulk create/update translations
     */
    public function bulkStore(Request $request, $locale): JsonResponse
    {
        try {
            // Validate the request
            $request->validate([
                'translations' => 'required|array',
                'translations.*.key' => 'required|string|max:255',
                'translations.*.value' => 'required|string',
            ]);

            $createdCount = $this->localizationRepo->bulkCreateOrUpdateTranslations(
                $locale,
                $request->input('translations')
            );

            return $this->successJsonResponse("{$createdCount} translations created/updated successfully");
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }
}