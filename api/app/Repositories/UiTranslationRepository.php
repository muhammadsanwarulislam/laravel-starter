<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UiTranslation;

class UiTranslationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new UiTranslation());
    }

    public function getTranslationsByGroupAndLocale(string $group, string $locale)
    {
        return $this->model->where('group', $group)
            ->whereHas('language', function ($query) use ($locale) {
                $query->where('code', $locale);
            })
            ->get()
            ->pluck('value', 'key');
    }
}