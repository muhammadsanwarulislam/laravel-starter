<?php
declare(strict_types=1);
namespace Repository\Localization;

use App\Models\Language;
use App\Models\UiTranslation;
use Repository\BaseRepository;

class LocalizationRepository extends BaseRepository
{
    const TRANSLATION_API_ENDPOINT_NAME = 'translations';
    const LANGUAGES_API_ENDPOINT_NAME = 'languages';
    
    public function model()
    {
        return Language::class;
    }
    
    public function getActiveLanguages()
    {
        return $this->model()::where('is_active', true)->orderBy('sort_order')->get();
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

    public function deleteTranslation($id)
    {
        return UiTranslation::destroy($id);
    }

    /**
     * Delete translation by key and locale
     */
    public function deleteTranslationByKey($locale, $key, $group = 'ui')
    {
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return false;
        }

        return UiTranslation::where([
            'language_id' => $language->id,
            'key' => $key,
            'group' => $group
        ])->delete() > 0;
    }

    /**
     * Bulk create or update translations
     */
    public function bulkCreateOrUpdateTranslations($locale, $translations, $group = 'ui')
    {
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return 0;
        }

        $createdCount = 0;

        foreach ($translations as $translation) {
            $result = UiTranslation::updateOrCreate(
                [
                    'language_id' => $language->id,
                    'key' => $translation['key'],
                    'group' => $group
                ],
                [
                    'value' => $translation['value']
                ]
            );

            if ($result) {
                $createdCount++;
            }
        }

        return $createdCount;
    }

    /**
     * Get translation by key and locale
     */
    public function getTranslationByKey($locale, $key, $group = 'ui')
    {
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return null;
        }

        return UiTranslation::where([
            'language_id' => $language->id,
            'key' => $key,
            'group' => $group
        ])->first();
    }
}