<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

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
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    // Cached permissions for performance
    public function cachedPermissions(): array
    {
        $cacheKey = "role_{$this->id}_permissions";

        return cache()->remember($cacheKey, 3600, function () {
            return $this->permissions()->get()->pluck('slug')->toArray();
        });
    }

    public function hasPermission($permissionSlug): bool
    {
        $permissions = $this->cachedPermissions();
        return in_array($permissionSlug, $permissions);
    }

    public function clearCachedPermissions(): void
    {
        cache()->forget("role_{$this->id}_permissions");

        $this->users()
            ->select('users.id')
            ->chunk(100, function (Collection $users): void {
                foreach ($users as $user) {
                    cache()->forget("user_{$user->id}_roles");
                    cache()->forget("user_{$user->id}_permissions");
                }
            });
    }

    protected static function booted(): void
    {
        static::saved(function (self $role): void {
            $role->clearCachedPermissions();
        });

        static::deleted(function (self $role): void {
            $role->clearCachedPermissions();
        });
    }
}
