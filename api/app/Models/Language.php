<?php

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

    public function getUiTranslationsArray()
    {
        return $this->uiTranslations()
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }
}
