<?php
declare(strict_types=1);

namespace App\Services\Language;

use App\Models\Language;
use App\Models\Translation;
use Repository\Language\LanguageRepository;

class LanguageService
{
    public function __construct(
        protected LanguageRepository $languageRepository
    ) {}
    
    public function getLanguages($requestData)
    {
        $offset         = $requestData['offset'] ?? 1;
        $limit          = $requestData['limit'] ?? 10;
        $option         = $requestData['option'] ?? 'list';
        $searchData     = $requestData['searchData'] ?? null;
        $searchFields   = $requestData['searchFields'] ?? null;

        $result = $this->languageRepository->getAll($offset, $limit, $searchData, $searchFields, $option);
        
        return [
            'languages' => $result['result'],
            'pagination' => [
                'total'         => $result['total_count'],
                'per_page'      => $result['per_page'],
                'current_page'  => $result['current_page'],
                'last_page'     => $result['last_page'],
                'from'          => (($offset - 1) * $limit) + 1,
                'to'            => min($offset * $limit, $result['total_count'])
            ],
            'metadata'          => $this->languageRepository->metadata($result['total_count'], 'success')
        ];
    }

    public function createLanguage(array $data)
    {
        // Extract translations from the data
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Ensure name is set from English translation if available
        if (!isset($data['name']) && isset($translations['name']['en'])) {
            $data['name'] = $translations['name']['en'];
        }

        // If setting as default, unset other defaults
        if ($data['is_default'] ?? false) {
            $this->languageRepository->model()::where('is_default', true)->update(['is_default' => false]);
        }
        
        $language = $this->languageRepository->create($data);

        // Save translations
        if (!empty($translations)) {
            $this->saveTranslations($language, $translations);
        }

        return $language;
    }

    public function updateLanguage(array $data, string $id)
    {
        // Extract translations from the data
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Ensure name is set from English translation if available
        if (!isset($data['name']) && isset($translations['name']['en'])) {
            $data['name'] = $translations['name']['en'];
        }

        // If setting as default, unset other defaults
        if ($data['is_default'] ?? false) {
            $this->languageRepository->model()::where('is_default', true)
                                         ->where('id', '!=', $id)
                                         ->update(['is_default' => false]);
        }
        
        $language = $this->languageRepository->updateByID($id, $data);

        // Save translations
        if (!empty($translations)) {
            $this->saveTranslations($language, $translations);
        }

        return $language;
    }

    public function getLanguageById($id)
    {
        return $this->languageRepository->findByID($id);
    }

    public function deleteLanguageById($id)
    {
        $language = $this->languageRepository->findByID($id);
        
        // Prevent deletion of default language
        if ($language->is_default) {
            throw new \Exception('Cannot delete the default language.');
        }
        
        return $this->languageRepository->deletedByID($id);
    }

    protected function saveTranslations($user, $translations)
    {
        foreach ($translations as $attribute => $langValues) {
            foreach ($langValues as $langCode => $value) {
                $language = Language::where('code', $langCode)->first();

                if (!$language) {
                    continue;
                }

                Translation::updateOrCreate(
                    [
                        'translatable_type' => get_class($user),
                        'translatable_id'   => $user->id,
                        'language_id'       => $language->id,
                        'attribute'         => $attribute,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}