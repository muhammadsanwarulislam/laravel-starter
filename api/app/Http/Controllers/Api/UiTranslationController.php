<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\UiTranslation;
use App\Http\Controllers\Controller;
use App\Services\LocalizationService;
use Illuminate\Support\Facades\Validator;

class UiTranslationController extends Controller
{
    protected $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    public function getUiTranslations(Request $request)
    {
        $group = $request->input('group', 'ui');
        $translations = $this->localizationService->getUiTranslations($group);

        return $this->success($translations, 'UI translations retrieved successfully');
    }

    public function getUiTranslation($key, Request $request)
    {
        $locale = $request->input('locale') ?: $this->localizationService->getCurrentLocale();
        $group = $request->input('group', 'ui');

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
        // $this->localizationService->clearCache();

        return $this->success($translation, 'Translation saved successfully');
    }

    public function deleteTranslation($key)
    {
        UiTranslation::where('key', $key)->delete();
        return $this->success(null, 'Translation deleted successfully');
    }
}