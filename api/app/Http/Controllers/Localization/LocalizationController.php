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
    public function getTranslations($locale): JsonResponse
    {
        try {
            $translations = $this->localizationRepo->getTranslationsByLocale($locale);

            if (empty($translations)) {
                return $this->errorJsonResponse('No translations found for the specified locale');
            }

            return $this->successJsonResponse('Translations fetched successfully', $translations);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch translations'], 500);
        }
    }

    /**
     * Update translation for specific key
     */
    public function updateTranslation(Request $request, $locale): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
            'group' => 'sometimes|string|max:50'
        ]);

        try {
            $translation = $this->localizationRepo->createOrUpdateTranslation(
                $locale,
                $validated['key'],
                $validated['value'],
                $validated['group'] ?? 'ui'
            );

            if (!$translation) {
                return $this->errorJsonResponse('Language not found');
            }

            return $this->successJsonResponse('Translation updated successfully', $translation);
        } catch (\Exception $e) {
            return $this->errorJsonResponse('Failed to update translation');
        }
    }

    /**
     * Bulk update translations
     */
    public function bulkUpdateTranslations(Request $request, $locale): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'group' => 'sometimes|string|max:50'
        ]);

        try {
            $success = $this->localizationRepo->bulkUpdateTranslations(
                $locale,
                $validated['translations'],
                $validated['group'] ?? 'ui'
            );

            if (!$success) {
                return $this->errorJsonResponse('Language not found or invalid translations data');
            }

            return $this->successJsonResponse('Translations updated successfully');
        } catch (\Exception $e) {
            return $this->errorJsonResponse('Failed to bulk update translations');
        }
    }

    /**
     * Export translations as JSON file
     */
    public function exportTranslations($locale): JsonResponse
    {
        try {
            $jsonContent = $this->localizationRepo->exportTranslationsToJson($locale);

            return response()->json([
                'locale' => $locale,
                'translations' => json_decode($jsonContent, true)
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonResponse('Failed to export translations');
        }
    }

    /**
     * Import translations from JSON
     */
    public function importTranslations(Request $request, $locale): JsonResponse
    {
        $validated = $request->validate([
            'json_content' => 'required|string'
        ]);

        try {
            $success = $this->localizationRepo->importTranslationsFromJson(
                $locale,
                $validated['json_content']
            );

            if (!$success) {
                return $this->errorJsonResponse('Invalid JSON or language not found');
            }

            return $this->successJsonResponse('Translations imported successfully');
        } catch (\Exception $e) {
            return $this->errorJsonResponse('Failed to import translations');
        }
    }
}
