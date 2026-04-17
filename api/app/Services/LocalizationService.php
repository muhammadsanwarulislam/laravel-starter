<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\LanguageRepository;
use App\Repositories\UiTranslationRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LocalizationService
{
    protected $currentLocale;
    protected $defaultLocale;
    protected $availableLocales;

    public function __construct(protected LanguageRepository $languageRepo, protected UiTranslationRepository $uiTranslationRepository)
    {
        $this->loadLanguages();
        $this->detectLocale();

    }

    private function loadLanguages()
    {
        $this->availableLocales = $this->languageRepo->getActiveLanguages()->keyBy('code');

        $this->defaultLocale = $this->availableLocales->where('is_default', true)->first() ?? $this->availableLocales->first();
    }

    private function detectLocale()
    {
        $locale = Session::get('locale')
            ?? $this->normalizeLocale(request()->header('X-Locale'))
            ?? $this->normalizeLocale(request()->header('Accept-Language'))
            ?? $this->getBrowserLocale()
            ?? $this->defaultLocale?->code
            ?? config('app.locale');

        if (!$this->setLocale($locale)) {
            $fallbackLocale = $this->defaultLocale?->code ?? config('app.locale');
            $this->currentLocale = $fallbackLocale;
            App::setLocale($fallbackLocale);
            Session::put('locale', $fallbackLocale);
        }
    }

    public function setLocale(string $locale): bool
    {
        $normalizedLocale = $this->normalizeLocale($locale);

        if ($normalizedLocale && isset($this->availableLocales[$normalizedLocale])) {
            $this->currentLocale = $normalizedLocale;
            App::setLocale($normalizedLocale);
            Session::put('locale', $normalizedLocale);

            return true;
        }

        if (isset($this->availableLocales[$locale])) {
            $this->currentLocale = $locale;
            App::setLocale($locale);
            Session::put('locale', $locale);

            return true;
        }

        return false;
    }

    public function getCurrentLocale(): string
    {
        return $this->currentLocale ?? $this->defaultLocale?->code ?? config('app.locale');
    }

    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    public function getUiTranslations(string $group = 'ui'): array
    {
        return $this->uiTranslationRepository->getTranslationsByGroupAndLocale($group, $this->currentLocale)->toArray();
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
        return $this->normalizeLocale(request()->server('HTTP_ACCEPT_LANGUAGE'));
    }

    private function normalizeLocale(?string $locale): ?string
    {
        if (!$locale) {
            return null;
        }

        $primaryLocale = trim(explode(',', $locale)[0]);
        $normalizedLocale = str_replace('_', '-', $primaryLocale);

        if (isset($this->availableLocales[$normalizedLocale])) {
            return $normalizedLocale;
        }

        $shortLocale = strtolower(substr($normalizedLocale, 0, 2));

        return isset($this->availableLocales[$shortLocale]) ? $shortLocale : null;
    }

    public function clearCache(): void
    {
        Cache::forget('available_locales');
        Cache::forget('active_languages');
        $this->loadLanguages();
    }
}
