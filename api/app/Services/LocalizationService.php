<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Language;
use App\Models\UiTranslation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LocalizationService
{
    protected $currentLocale;
    protected $defaultLocale;
    protected $availableLocales;

    public function __construct()
    {
        $this->loadLanguages();
        $this->detectLocale();
    }

    private function loadLanguages()
    {
        $this->availableLocales = Cache::remember('available_locales', 3600, function () {
            return Language::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('code');
        });

        $this->defaultLocale = $this->availableLocales->where('is_default', true)->first()
            ?? $this->availableLocales->first();
    }

    private function detectLocale()
    {
        $locale = Session::get('locale')
            ?? request()->header('Accept-Language')
            ?? $this->getBrowserLocale()
            ?? $this->defaultLocale->code;

        $this->setLocale($locale);
    }

    public function setLocale(string $locale): bool
    {
        if (isset($this->availableLocales[$locale])) {
            $this->currentLocale = $locale;
            App::setLocale($locale);
            Session::put('locale', $locale);

            if (auth()->check()) {
                auth()->user()->update(['ui_locale' => $locale]);
            }

            return true;
        }

        return false;
    }

    public function getCurrentLocale(): string
    {
        return $this->currentLocale;
    }

    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    public function getUiTranslations(string $group = 'ui'): array
    {
        $cacheKey = "ui_translations_{$group}_{$this->currentLocale}";

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return UiTranslation::where('group', $group)
                ->whereHas('language', function ($query) {
                    $query->where('code', $this->currentLocale);
                })
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public function trans(string $key, array $replace = []): string
    {
        $translations = $this->getUiTranslations();

        if (isset($translations[$key])) {
            $translation = $translations[$key];

            foreach ($replace as $placeholder => $value) {
                $translation = str_replace(":{$placeholder}", $value, $translation);
            }

            return $translation;
        }

        return trans($key, $replace, $this->currentLocale);
    }

    private function getBrowserLocale(): ?string
    {
        $locale = substr(request()->server('HTTP_ACCEPT_LANGUAGE') ?? '', 0, 2);
        return isset($this->availableLocales[$locale]) ? $locale : null;
    }

    public function clearCache(): void
    {
        Cache::forget('available_locales');
        Cache::forget('active_languages');
        $this->loadLanguages();
    }

    public function isLocaleSupported(string $locale): bool
    {
        return Language::where('code', $locale)
            ->where('is_active', true)
            ->exists();
    }
}
