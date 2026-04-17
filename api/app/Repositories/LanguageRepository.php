<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Language;

class LanguageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Language());
    }

    public function getActiveLanguages()
    {
        return $this->model->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}