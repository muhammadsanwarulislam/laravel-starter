<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\LanguageRepository;

class LanguageService
{
    public function __construct(protected LanguageRepository $languageRepo)
    {
        
    }

    public function getActiveLanguages()
    {
        return $this->languageRepo->getActiveLanguages();
    }
}
