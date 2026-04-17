<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PermissionService
{
    public function __construct(protected PermissionRepository $permissionRepository) {}

    public function getFilteredPermissions(
        ?string $search = null,
        ?string $module = null,
        string $sortField = 'module',
        string $sortOrder = 'asc',
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->permissionRepository->getFilteredPermissions(
            search: $search,
            module: $module,
            sortField: $sortField,
            sortOrder: $sortOrder,
            perPage: $perPage
        );
    }

    public function createPermission(array $data): Permission
    {
        $permission = $this->permissionRepository->createPermission([
            'name' => $data['name'],
            'slug' => $this->buildSlug($data['name'], $data['module']),
            'module' => Str::slug($data['module']),
            'description' => $data['description'] ?? null,
        ]);

        self::invalidatePermissionCaches();

        return $permission;
    }

    public function updatePermission(int $permissionId, array $data): Permission
    {
        $permission = $this->permissionRepository->updatePermission($permissionId, [
            'name' => $data['name'],
            'slug' => $this->buildSlug($data['name'], $data['module']),
            'module' => Str::slug($data['module']),
            'description' => $data['description'] ?? null,
        ]);

        self::invalidatePermissionCaches();

        return $permission;
    }

    public function deletePermission(Permission $permission): void
    {
        $permission->delete();

        self::invalidatePermissionCaches();
    }

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

        self::invalidatePermissionCaches();
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

    public static function invalidatePermissionCaches(): void
    {
        Cache::forget('permission_modules');

        Role::all()->each(function ($role): void {
            Cache::forget("role_{$role->id}_permissions");
        });
    }

    protected function buildSlug(string $name, string $module): string
    {
        return Str::slug($name) . '-' . Str::slug($module);
    }
}
