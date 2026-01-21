<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpValidation extends Model
{
    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'is_used',
        'type',
        'attempts',
        'is_locked',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
        'is_locked' => 'boolean',
        'attempts' => 'integer',
    ];
}
