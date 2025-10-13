<?php
declare(strict_types=1);

namespace Repository\Language;

use App\Models\Language;
use Repository\BaseRepository;

class LanguageRepository extends BaseRepository
{
    const RESOURCE_NAME = 'languages';

    public function model()
    {
        return Language::class;
    }
}