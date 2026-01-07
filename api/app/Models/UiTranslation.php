<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UiTranslation extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'language_id'
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
