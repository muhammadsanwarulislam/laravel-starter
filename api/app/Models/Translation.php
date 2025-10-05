<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
    protected $fillable = [
        'translatable_type', 'translatable_id', 'language_id', 'attribute', 'value'
    ];

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
        return $query->whereHas('language', function($q) use ($locale) {
            $q->where('code', $locale);
        });
    }

    // Helper method to get value by locale
    public function scopeForAttribute($query, $attribute, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        return $query->where('attribute', $attribute)
            ->whereHas('language', function($q) use ($locale) {
                $q->where('code', $locale);
            });
    }
}