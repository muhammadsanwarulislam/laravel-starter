<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LocalizationService;

class LocalizationController extends Controller
{
    protected $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    public function getContentTranslations($model, $id, Request $request)
    {
        $modelClass = "App\\Models\\{$model}";
        if (!class_exists($modelClass)) {
            return $this->error('Model not found', null, 404);
        }

        $modelInstance = $modelClass::find($id);
        if (!$modelInstance) {
            return $this->notFound('Resource not found');
        }

        $locale = $request->input('locale') ?: $this->localizationService->getCurrentLocale();

        $translations = \App\Models\Translation::getForModel($modelInstance, $locale);

        return $this->success($translations, 'Content translations retrieved successfully');
    }
}