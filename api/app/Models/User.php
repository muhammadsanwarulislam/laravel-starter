<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasPermissions;
use App\Traits\Translatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPermissions, Translatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code_id',
        'phone',
        'status'
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $translatable = ['name', 'bio'];
    protected $with = ['roles'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'status'            => 'boolean',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function files()
    {
        return $this->hasMany(FileManager::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roleSlug)
    {
        $cacheKey = "user_{$this->id}_has_role_{$roleSlug}";

        return cache()->remember($cacheKey, 300, function () use ($roleSlug) {
            return $this->roles()->where('slug', $roleSlug)->exists();
        });
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function isActive()
    {
        return $this->status;
    }

    public function cachedRoles()
    {
        $cacheKey = "user_{$this->id}_roles";

        return cache()->remember($cacheKey, 300, function () {
            return $this->roles()->with('permissions')->get();
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($user){
            $user->roles()->detach();
        });
    }
}
