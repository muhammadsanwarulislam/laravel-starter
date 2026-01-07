<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
        'level'
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    // Cached permissions for performance
    public function cachedPermissions()
    {
        $cacheKey = "role_{$this->id}_permissions";

        return cache()->remember($cacheKey, 3600, function () {
            return $this->permissions()->get()->pluck('slug')->toArray();
        });
    }

    public function hasPermission($permissionSlug)
    {
        $permissions = $this->cachedPermissions();
        return in_array($permissionSlug, $permissions);
    }

    // Clear cache on update
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($role) {
            cache()->forget("role_{$role->id}_permissions");

            // Clear user role caches
            $role->users()->chunk(100, function ($users) {
                foreach ($users as $user) {
                    cache()->forget("user_{$user->id}_roles");
                    cache()->forget("user_{$user->id}_permissions");
                }
            });
        });
    }
}
