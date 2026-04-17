<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LocalizationService;
use Illuminate\Support\Facades\Validator;

class LocaleController extends Controller
{
    protected $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
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
}