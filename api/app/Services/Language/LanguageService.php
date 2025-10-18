<?php
declare(strict_types=1);

namespace App\Services\Language;

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
        // If setting as default, unset other defaults
        if ($data['is_default'] ?? false) {
            $this->languageRepository->model()::where('is_default', true)->update(['is_default' => false]);
        }
        
        return $this->languageRepository->create($data);
    }

    public function updateLanguage(array $data, string $id)
    {
        // If setting as default, unset other defaults
        if ($data['is_default'] ?? false) {
            $this->languageRepository->model()::where('is_default', true)
                                         ->where('id', '!=', $id)
                                         ->update(['is_default' => false]);
        }
        
        return $this->languageRepository->updateByID($id, $data);
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
}