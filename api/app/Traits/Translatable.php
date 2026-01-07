<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Translation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Translatable
{
    /**
     * Get all translations for this model
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get translation for specific attribute and locale
     */
    public function translate(string $attribute, ?string $locale = null): mixed
    {
        $locale = $locale ?: app()->getLocale();

        // Method 1: Use Translation model method if available
        if (method_exists(Translation::class, 'getAttributeTranslation')) {
            return Translation::getAttributeTranslation($this, $attribute, $locale);
        }

        // Method 2: Fallback to direct query
        $translation = $this->translations()
            ->where('attribute', $attribute)
            ->whereHas('language', function ($q) use ($locale) {
                $q->where('code', $locale);
            })
            ->first();

        return $translation ? $translation->value : $this->getAttribute($attribute);
    }

    /**
     * Set translation for specific attribute and locale
     */
    public function setTranslation(string $attribute, string $value, ?string $locale = null): static
    {
        $locale = $locale ?: app()->getLocale();

        // Method 1: Use Translation model method if available
        if (method_exists(Translation::class, 'updateTranslations')) {
            Translation::updateTranslations($this, [$attribute => $value], $locale);
            return $this;
        }

        // Method 2: Fallback to manual update
        $language = Language::where('code', $locale)->first();

        if ($language) {
            $this->translations()->updateOrCreate(
                [
                    'language_id' => $language->id,
                    'attribute' => $attribute,
                ],
                ['value' => $value]
            );
        }

        return $this;
    }

    /**
     * Set multiple translations at once
     */
    public function setTranslations(array $translations, ?string $locale = null): static
    {
        $locale = $locale ?: app()->getLocale();

        // Method 1: Use Translation model method if available
        if (method_exists(Translation::class, 'updateTranslations')) {
            Translation::updateTranslations($this, $translations, $locale);
            return $this;
        }

        // Method 2: Fallback to manual update
        $language = Language::where('code', $locale)->first();

        if ($language) {
            foreach ($translations as $attribute => $value) {
                $this->translations()->updateOrCreate(
                    [
                        'language_id' => $language->id,
                        'attribute' => $attribute,
                    ],
                    ['value' => $value]
                );
            }
        }

        return $this;
    }

    /**
     * Get all translations for the model
     */
    public function getTranslations(?string $attribute = null, ?string $locale = null): mixed
    {
        $locale = $locale ?: app()->getLocale();

        // Method 1: Use Translation model method if available
        if (method_exists(Translation::class, 'getForModel')) {
            $translations = Translation::getForModel($this, $locale);

            if ($attribute) {
                return $translations[$attribute] ?? null;
            }

            return $translations;
        }

        // Method 2: Fallback to manual query
        $query = $this->translations()
            ->whereHas('language', function ($q) use ($locale) {
                $q->where('code', $locale);
            });

        if ($attribute) {
            $translation = $query->where('attribute', $attribute)->first();
            return $translation ? $translation->value : null;
        }

        return $query->get()->pluck('value', 'attribute')->toArray();
    }

    /**
     * Check if translation exists
     */
    public function hasTranslation(string $attribute, ?string $locale = null): bool
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()
            ->where('attribute', $attribute)
            ->whereHas('language', function ($q) use ($locale) {
                $q->where('code', $locale);
            })
            ->exists();
    }

    /**
     * Get available locales for an attribute
     */
    public function getAvailableLocalesForAttribute(string $attribute): array
    {
        return $this->translations()
            ->where('attribute', $attribute)
            ->with('language')
            ->get()
            ->pluck('language.code')
            ->toArray();
    }

    /**
     * Magic method to get translated attributes
     */
    public function __get($key): mixed
    {
        if (in_array($key, $this->translatable ?? [])) {
            return $this->translate($key);
        }

        if (str_ends_with($key, '_translated')) {
            $attribute = str_replace('_translated', '', $key);
            if (in_array($attribute, $this->translatable ?? [])) {
                return $this->translate($attribute);
            }
        }

        return parent::__get($key);
    }

    /**
     * Magic method to set translated attributes
     */
    public function __set($key, $value): void
    {
        if (in_array($key, $this->translatable ?? [])) {
            $this->setTranslation($key, $value, app()->getLocale());
            parent::__set($key, $value);
        } else {
            parent::__set($key, $value);
        }
    }

    /**
     * Eager load translations for current locale
     */
    public function scopeWithTranslations($query, $locale = null): mixed
    {
        $locale = $locale ?: app()->getLocale();

        return $query->with(['translations' => function ($q) use ($locale) {
            $q->whereHas('language', function ($q2) use ($locale) {
                $q2->where('code', $locale);
            });
        }]);
    }

    /**
     * Eager load all translations
     */
    public function scopeWithAllTranslations($query): mixed
    {
        return $query->with('translations.language');
    }

    /**
     * Override toArray to include translated attributes
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        foreach ($this->translatable ?? [] as $attribute) {
            if (!isset($array[$attribute])) {
                $array[$attribute] = $this->translate($attribute);
            }
        }

        return $array;
    }

    /**
     * Clear translation cache for this model
     */
    public function clearTranslationCache(?string $attribute = null, ?string $locale = null): void
    {
        if (method_exists(Translation::class, 'clearModelTranslationCache')) {
            Translation::clearModelTranslationCache($this, $locale);
        }
    }
}
