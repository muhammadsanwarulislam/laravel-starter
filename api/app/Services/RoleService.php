<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class RoleService
{
    public function __construct(protected RoleRepository $roleRepository) {}

    public function getFilteredRoles(
        ?string $search = null,
        ?string $status = null,
        string $sortField = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 5
    ): LengthAwarePaginator {
        return $this->roleRepository->getFilteredRoles(
            $search,
            $status,
            $sortField,
            $sortOrder,
            $perPage
        );
    }

    public function createRole(array $data): Role
    {
        $role = $this->roleRepository->createRole($data + ['slug' => Str::slug($data['name'])]);

        if (array_key_exists('permissions', $data)) {
            $role->permissions()->sync($data['permissions'] ?? []);
            $role->clearCachedPermissions();
        }

        return $role->load('permissions');
    }

    public function updateRole(int $roleId, array $data): Role
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        $role = $this->roleRepository->updateRole($roleId, $data);

        if ($permissions !== null) {
            $role->permissions()->sync($permissions);
            $role->clearCachedPermissions();
        }

        return $role->load('permissions');
    }

    public function syncPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->sync($permissionIds);
        $role->clearCachedPermissions();

        return $role->load('permissions');
    }
}
