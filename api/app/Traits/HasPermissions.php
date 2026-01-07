<?php

declare(strict_types=1);

namespace App\Traits;

trait HasPermissions
{
    // Get cached permissions for user
    public function cachedPermissions(): array
    {
        $cacheKey = "user_{$this->id}_permissions";

        return cache()->remember($cacheKey, 300, function () {
            $permissions = [];

            foreach ($this->cachedRoles() as $role) {
                $rolePermissions = $role->cachedPermissions();
                $permissions = array_merge($permissions, $rolePermissions);
            }

            return array_unique($permissions);
        });
    }

    // Check permission with cache
    public function hasPermission($permissionSlug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->cachedPermissions();
        return in_array($permissionSlug, $permissions);
    }

    public function canAccessModule($module): bool
    {
        $permissions = ['view', 'create', 'edit', 'delete'];

        foreach ($permissions as $permission) {
            if ($this->hasPermission("{$permission}-{$module}")) {
                return true;
            }
        }

        return false;
    }

    // Clear permission cache
    public function clearPermissionCache(): void
    {
        cache()->forget("user_{$this->id}_permissions");
        cache()->forget("user_{$this->id}_roles");
    }
}
