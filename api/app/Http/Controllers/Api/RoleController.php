<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->success($roles, 'Roles retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:100',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $role = Role::create($request->except('permissions'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return $this->success($role->load('permissions'), 'Role created successfully', 201);
    }

    public function show(Role $role)
    {
        return $this->success($role->load('permissions'), 'Role retrieved successfully');
    }

    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return $this->error('System roles cannot be modified', null, 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'level' => 'sometimes|integer|min:0|max:100',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $role->update($request->except('permissions'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return $this->success($role->load('permissions'), 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
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
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $role->permissions()->sync($request->permissions);

        return $this->success($role->load('permissions'), 'Permissions assigned successfully');
    }
}
