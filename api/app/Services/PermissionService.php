<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    public static function getModules(): array
    {
        return Cache::remember('permission_modules', 3600, function () {
            return Permission::distinct()
                ->orderBy('module')
                ->pluck('module')
                ->toArray();
        });
    }

    public static function syncPermissions(array $modules): void
    {
        $permissions = [];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $slug = "{$action}-{$module}";
                $permissions[] = [
                    'slug' => $slug,
                    'name' => ucfirst($action) . ' ' . ucfirst($module),
                    'module' => $module,
                    'description' => "Allows {$action} on {$module} module"
                ];
            }
        }

        // Update or create permissions
        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['slug' => $permissionData['slug']],
                $permissionData
            );
        }

        // Remove old permissions
        $validSlugs = array_column($permissions, 'slug');
        Permission::whereNotIn('slug', $validSlugs)->delete();

        Cache::forget('permission_modules');

        // Clear role permission caches
        Role::all()->each(function ($role) {
            Cache::forget("role_{$role->id}_permissions");
        });
    }

    public static function getPermissionsByModule(string $module): array
    {
        return Permission::where('module', $module)
            ->orderBy('slug')
            ->get()
            ->toArray();
    }

    public static function userHasPermission($user, string $permission): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission($permission);
    }
}
