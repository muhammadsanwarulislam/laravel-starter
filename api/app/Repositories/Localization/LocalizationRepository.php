<?php
declare(strict_types=1);
namespace Repository\Localization;

use App\Models\Language;
use App\Models\UiTranslation;
use Repository\BaseRepository;

class LocalizationRepository extends BaseRepository
{
    public function model()
    {
        return Language::class;
    }
    public function getActiveLanguages()
    {
        return Language::active()
            ->orderBy('sort_order')
            ->get();
    }

    public function getDefaultLanguage()
    {
        return Language::default()->first() ?? Language::active()->first();
    }

    public function getTranslationsByLocale($locale)
    {
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return [];
        }

        return $language->getUiTranslationsArray();
    }

    public function createOrUpdateTranslation($locale, $key, $value, $group = 'ui')
    {
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return null;
        }

        return UiTranslation::updateOrCreate(
            [
                'language_id' => $language->id,
                'key' => $key,
                'group' => $group
            ],
            [
                'value' => $value
            ]
        );
    }

    public function bulkUpdateTranslations($locale, $translations, $group = 'ui')
    {
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return false;
        }

        foreach ($translations as $key => $value) {
            UiTranslation::updateOrCreate(
                [
                    'language_id' => $language->id,
                    'key' => $key,
                    'group' => $group
                ],
                [
                    'value' => $value
                ]
            );
        }

        return true;
    }

    public function getTranslationGroups()
    {
        return UiTranslation::distinct()
            ->pluck('group')
            ->toArray();
    }

    public function exportTranslationsToJson($locale)
    {
        $translations = $this->getTranslationsByLocale($locale);
        return json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function importTranslationsFromJson($locale, $jsonContent)
    {
        $translations = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return $this->bulkUpdateTranslations($locale, $translations);
    }
}