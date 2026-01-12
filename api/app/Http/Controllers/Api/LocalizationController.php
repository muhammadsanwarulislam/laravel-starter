<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\UiTranslation;
use App\Http\Controllers\Controller;
use App\Services\LocalizationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
class LocalizationController extends Controller
{
    protected $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    public function getLanguages()
    {
        $languages = $this->localizationService->getAvailableLocales();
        return $this->success($languages, 'Languages retrieved successfully');
    }

    public function getCurrentLocale()
    {
        return $this->success([
            'locale' => $this->localizationService->getCurrentLocale(),
            'locales' => $this->localizationService->getAvailableLocales()
        ], 'Current locale retrieved');
    }

    public function setLocale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        if ($this->localizationService->setLocale($request->locale)) {
            return $this->success([
                'locale' => $this->localizationService->getCurrentLocale(),
                'translations' => $this->localizationService->getUiTranslations()
            ], 'Locale changed successfully');
        }

        return $this->error('Invalid locale', null, 400);
    }

    public function getUiTranslations(Request $request)
    {
        $group = $request->get('group', 'ui');
        $translations = $this->localizationService->getUiTranslations($group);
        
        return $this->success($translations, 'UI translations retrieved successfully');
    }

    public function getUiTranslation($key, Request $request)
    {
        $locale = $request->get('locale') ?: $this->localizationService->getCurrentLocale();
        $group = $request->get('group', 'ui');
        
        $translation = UiTranslation::where('key', $key)
            ->where('group', $group)
            ->whereHas('language', function ($query) use ($locale) {
                $query->where('code', $locale);
            })
            ->first();
        
        if (!$translation) {
            return $this->notFound('Translation not found');
        }
        
        return $this->success($translation, 'Translation retrieved successfully');
    }

    public function storeUiTranslation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'locale' => 'required|string|exists:languages,code',
            'group' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $language = Language::where('code', $request->locale)->first();
        
        $translation = UiTranslation::updateOrCreate(
            [
                'key' => $request->key,
                'language_id' => $language->id,
                'group' => $request->group ?? 'ui'
            ],
            ['value' => $request->value]
        );

        // Clear cache
        $this->localizationService->clearCache();

        return $this->success($translation, 'Translation saved successfully');
    }

    public function getContentTranslations($model, $id, Request $request)
    {
        try {
            // Sanitize model name
            $modelName = ucfirst(strtolower($model));
            $modelClass = "App\\Models\\{$modelName}";
            
            if (!class_exists($modelClass)) {
                return $this->error("Model '{$modelName}' not found", null, 404);
            }

            // Check if model uses translations
            if (!method_exists($modelClass, 'translations')) {
                return $this->error("Model '{$modelName}' does not support translations", null, 400);
            }

            $modelInstance = $modelClass::find($id);
            
            if (!$modelInstance) {
                return $this->error("{$modelName} with ID {$id} not found", null, 404);
            }

            $locale = $request->get('locale') ?: $this->localizationService->getCurrentLocale();
            
            // Validate locale
            if (!$this->localizationService->isLocaleSupported($locale)) {
                return $this->error("Locale '{$locale}' is not supported", null, 400);
            }
            
            // Get translations with caching - USING CORRECT Cache FACADE
            $cacheKey = "translations_{$model}_{$id}_{$locale}";
            $translations = Cache::remember($cacheKey, 3600, function () use ($modelInstance, $locale) {
                return \App\Models\Translation::getForModel($modelInstance, $locale);
            });
            
            // Include default values for untranslated attributes
            $translations = $this->enrichWithDefaults($modelInstance, $translations);
            
            return $this->success($translations, 'Content translations retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve translations', null, 500);
        }
    }

    private function enrichWithDefaults($model, $translations)
    {
        $defaultAttributes = $model->getTranslatableAttributes() ?? [];
        
        foreach ($defaultAttributes as $attribute) {
            if (!isset($translations[$attribute])) {
                $translations[$attribute] = $model->getAttribute($attribute);
            }
        }
        
        return $translations;
    }
}