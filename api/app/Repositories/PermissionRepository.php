<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Permission());
    }

    public function getFilteredPermissions(
        ?string $search = null,
        ?string $module = null,
        string $sortField = 'module',
        string $sortOrder = 'asc',
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($module) {
            $query->where('module', $module);
        }

        return $query->orderBy($sortField, $sortOrder)->paginate($perPage);
    }

    public function createPermission(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function updatePermission(int $permissionId, array $data): Permission
    {
        $permission = $this->model->findOrFail($permissionId);
        $permission->update($data);

        return $permission->fresh();
    }
}
