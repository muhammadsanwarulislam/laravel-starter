<?php

namespace App\Traits;

use App\Models\Translation;

trait Translatable
{
    protected $translationsToSave = [];

    /**
     * Boot the translatable trait
     */
    public static function bootTranslatable()
    {
        static::saved(function ($model) {
            if (!empty($model->translationsToSave)) {
                $model->saveTranslations();
            }
        });

        static::deleting(function ($model) {
            $model->translations()->delete();
        });
    }

    /**
     * Get all translations for this model
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get translation for specific attribute and locale
     */
    public function translate(string $attribute, string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->translations()
            ->whereHas('language', function($query) use ($locale) {
                $query->where('code', $locale);
            })
            ->where('attribute', $attribute)
            ->first();

        return $translation ? $translation->value : $this->getAttribute($attribute);
    }

    /**
     * Set translation for specific attribute and locale
     */
    public function setTranslation(string $attribute, string $value, string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $language = \App\Models\Language::where('code', $locale)->first();
        
        if (!$language) {
            throw new \Exception("Language with code {$locale} not found");
        }

        $translation = $this->translations()
            ->where('language_id', $language->id)
            ->where('attribute', $attribute)
            ->first();

        if ($translation) {
            $translation->update(['value' => $value]);
        } else {
            $this->translations()->create([
                'language_id' => $language->id,
                'attribute' => $attribute,
                'value' => $value
            ]);
        }

        return $this;
    }

    /**
     * Set multiple translations at once
     */
    public function setTranslations(array $translations)
    {
        foreach ($translations as $attribute => $localeValues) {
            foreach ($localeValues as $locale => $value) {
                $this->setTranslation($attribute, $value, $locale);
            }
        }

        return $this;
    }

    /**
     * Get all translations for the model
     */
    public function getTranslations(string $attribute = null)
    {
        $translations = $this->translations()
            ->with('language')
            ->get()
            ->groupBy('attribute');

        if ($attribute) {
            return $translations->get($attribute, collect())
                ->mapWithKeys(function ($translation) {
                    return [$translation->language->code => $translation->value];
                })
                ->toArray();
        }

        return $translations->map(function ($items) {
            return $items->mapWithKeys(function ($translation) {
                return [$translation->language->code => $translation->value];
            });
        })->toArray();
    }

    /**
     * Magic method to get translated attributes
     */
    public function __get($key)
    {
        // Check if it's a translatable attribute
        if (in_array($key, $this->translatable ?? [])) {
            return $this->translate($key);
        }
        
        return parent::__get($key);
    }

    /**
     * Magic method to set translated attributes for current locale
     */
    public function __set($key, $value)
    {
        if (in_array($key, $this->translatable ?? [])) {
            // Store for saving later
            if (!isset($this->translationsToSave[$key])) {
                $this->translationsToSave[$key] = [];
            }
            $this->translationsToSave[$key][app()->getLocale()] = $value;
            
            // Also set the original attribute as fallback
            parent::__set($key, $value);
        } else {
            parent::__set($key, $value);
        }
    }

    /**
     * Save all pending translations
     */
    public function saveTranslations()
    {
        foreach ($this->translationsToSave as $attribute => $localeValues) {
            foreach ($localeValues as $locale => $value) {
                $this->setTranslation($attribute, $value, $locale);
            }
        }
        
        $this->translationsToSave = [];
    }

    /**
     * Eager load translations for current locale
     */
    public function scopeWithTranslations($query, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        return $query->with(['translations' => function($q) use ($locale) {
            $q->whereHas('language', function($q2) use ($locale) {
                $q2->where('code', $locale);
            });
        }]);
    }
}