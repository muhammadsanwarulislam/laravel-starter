<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'direction',
        'is_active',
        'is_default',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function uiTranslations()
    {
        return $this->hasMany(UiTranslation::class);
    }

    // Get all UI translations for this language with caching
    public function getCachedUiTranslations()
    {
        $cacheKey = "language_{$this->id}_ui_translations";

        return cache()->remember($cacheKey, 3600, function () {
            return $this->uiTranslations()
                ->where('group', 'ui')
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    // Get specific translation with fallback
    public function getTranslation($key, $default = null)
    {
        $translations = $this->getCachedUiTranslations();
        return $translations[$key] ?? $default ?? $key;
    }

    // Clear translation cache
    public static function clearTranslationCache()
    {
        $languages = self::where('is_active', true)->get();
        foreach ($languages as $language) {
            cache()->forget("language_{$language->id}_ui_translations");
        }
        cache()->forget('active_languages');
    }
}
