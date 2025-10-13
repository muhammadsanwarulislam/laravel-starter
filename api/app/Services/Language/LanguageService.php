<?php
declare(strict_types=1);

namespace App\Services\Language;

use Repository\Language\LanguageRepository;

class LanguageService
{
    public function __construct(
        protected LanguageRepository $languageRepository
    ) {
    
    }

    public function getLanguages($requestData)
    {
        $offset         = $requestData['offset'];
        $limit          = $requestData['limit'];
        $option         = $requestData['option'];
        $searchData     = $requestData['searchData'] ?? null;
        $searchFields   = $requestData['searchFields'] ?? null;

        $languages = $this->languageRepository->getAll($offset, $limit, $searchData, $searchFields, $option);
        $totalCount = $languages['count'];

        return [
            'option'    =>  $option, 
            'offset'    =>  $offset, 
            'limit'     =>  $limit, 
            'totalCount'=>  $totalCount, 
            'languages' =>  $languages,
            'metaData'  =>  $languages['metadata']
        ];
    }

    public function createLanguage(array $data)
    {
        return $this->languageRepository->create($data);
    }

    public function updateLanguage(array $data, string $id, bool $isPatch = false)
    {
        return $this->languageRepository->updateByID($id, $data);
    }

    public function getLanguageById($userId)
    {
        return $this->languageRepository->findByID($userId);
    }

    public function deleteLanguageById($userId)
    {
        return$this->languageRepository->deletedByID($userId);
    }
}
