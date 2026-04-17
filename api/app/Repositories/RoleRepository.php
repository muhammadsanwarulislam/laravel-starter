<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function getFilteredRoles(
        ?string $search = null,
        ?string $status = null,
        string $sortField = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 5
    ): LengthAwarePaginator {
        $query = $this->model->with(['permissions']);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($perPage);
    }

    public function createRole(array $data): Role
    {
        return $this->model->create($data)->load('permissions');
    }

    public function updateRole(int $roleId, array $data): Role
    {
        $role = $this->model->findOrFail($roleId);
        $role->update($data);

        return $role->load('permissions');
    }
}
