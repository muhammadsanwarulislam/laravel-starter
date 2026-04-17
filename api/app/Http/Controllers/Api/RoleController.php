<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\CreateOrUpdateRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(protected RoleService $roleService)
    {
        
    }

    public function index(Request $request)
    {
        try {
            $roles = $this->roleService->getFilteredRoles(
                search: $request->search,
                status: $request->status,
                sortField: $request->input('sort_field', 'created_at'),
                sortOrder: $request->input('sort_order', 'desc'),
                perPage: (int)$request->input('limit', 5)
            );
            return $this->success($roles, 'Roles retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function store(CreateOrUpdateRequest $request)
    {
        try {
            $role = $this->roleService->createRole($request->validated());
            return $this->success($role->load('permissions'), 'Role created successfully', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function show(Role $role)
    {
        return $this->success($role->load('permissions'), 'Role retrieved successfully');
    }

    public function update(CreateOrUpdateRequest $request, Role $role)
    {
        if ($role->is_system && $request->hasAny(['name', 'description', 'level', 'permissions'])) {
            return $this->error('System roles cannot be modified', null, 403);
        }

        try {
            $updatedRole = $this->roleService->updateRole($role->id, $request->validated());
            return $this->success($updatedRole, 'Role updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function destroy(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        if ($role->is_system) {
            return $this->error('System roles cannot be deleted', null, 403);
        }

        if ($role->users()->count() > 0) {
            return $this->error('Cannot delete role that has users assigned', null, 400);
        }

        $role->delete();

        return $this->success(null, 'Role deleted successfully');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $updatedRole = $this->roleService->syncPermissions($role, $validated['permissions']);

        return $this->success($updatedRole, 'Permissions assigned successfully');
    }
}
