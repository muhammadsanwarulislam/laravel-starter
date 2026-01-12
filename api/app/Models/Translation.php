<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'language_id',
        'attribute',
        'value'
    ];

    protected $with = ['language'];

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeForLocale($query, $locale)
    {
        return $query->whereHas('language', function ($q) use ($locale) {
            $q->where('code', $locale);
        });
    }

    // ========== REQUIRED METHODS FOR TRANSLATABLE TRAIT ==========

    /**
     * Get attribute translation for a model
     */
    public static function getAttributeTranslation($model, $attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        $translation = self::where('translatable_type', get_class($model))
            ->where('translatable_id', $model->id)
            ->where('attribute', $attribute)
            ->whereHas('language', function ($q) use ($locale) {
                $q->where('code', $locale);
            })
            ->first();

        return $translation ? $translation->value : $model->getAttribute($attribute);
    }

    /**
     * Update translations for a model
     */
    public static function updateTranslations($model, array $translations, $locale)
    {
        $language = \App\Models\Language::where('code', $locale)->first();

        if (!$language) {
            return false;
        }

        foreach ($translations as $attribute => $value) {
            self::updateOrCreate(
                [
                    'translatable_type' => get_class($model),
                    'translatable_id' => $model->id,
                    'language_id' => $language->id,
                    'attribute' => $attribute,
                ],
                ['value' => $value]
            );
        }

        return true;
    }

    /**
     * Get all translations for a model
     */
    public static function getForModel($model, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $modelClass = get_class($model);
        $modelId = $model->id;
        
        $cacheKey = "translations_{$modelClass}_{$modelId}_{$locale}";
        
        return Cache::remember($cacheKey, 3600, function () use ($modelClass, $modelId, $locale) {
            return self::where('translatable_type', $modelClass)
                ->where('translatable_id', $modelId)
                ->whereHas('language', function ($q) use ($locale) {
                    $q->where('code', $locale);
                })
                ->pluck('value', 'attribute')
                ->toArray();
        });
    }

    /**
     * Clear translation cache for a model
     */
    public static function clearModelTranslationCache($model, $locale = null)
    {
        Cache::flush();
    }
}
