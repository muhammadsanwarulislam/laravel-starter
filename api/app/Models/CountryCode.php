<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{
    protected $fillable = [
        'name',
        'code',
        'dial_code',
        'status',
        'sort_order',
    ];
}
