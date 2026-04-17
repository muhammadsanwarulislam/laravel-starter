<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    public function index(Request $request)
    {
        $permissions = $this->permissionService->getFilteredPermissions(
            search: $request->input('search'),
            module: $request->input('module'),
            sortField: $request->input('sort_field', 'module'),
            sortOrder: $request->input('sort_order', 'asc'),
            perPage: (int) $request->input('limit', 15)
        );

        return $this->success($permissions, 'Permissions retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $permission = $this->permissionService->createPermission($validated);

        return $this->success($permission, 'Permission created successfully', 201);
    }

    public function show(Permission $permission)
    {
        return $this->success($permission, 'Permission retrieved successfully');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate($this->rules($permission->id));

        $updatedPermission = $this->permissionService->updatePermission($permission->id, $validated);

        return $this->success($updatedPermission, 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->exists()) {
            return $this->error('Cannot delete permission that is assigned to roles', null, 400);
        }

        $this->permissionService->deletePermission($permission);

        return $this->success(null, 'Permission deleted successfully');
    }

    public function getModules()
    {
        $modules = PermissionService::getModules();
        return $this->success($modules, 'Modules retrieved successfully');
    }

    public function getByModule($module)
    {
        $permissions = Permission::where('module', $module)->get();
        return $this->success($permissions, 'Permissions retrieved successfully');
    }

    public function sync(Request $request)
    {
        $request->validate([
            'modules' => 'required|array'
        ]);

        PermissionService::syncPermissions($request->modules);

        return $this->success(null, 'Permissions synced successfully');
    }

    protected function rules(?int $permissionId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($permissionId): void {
                    $slug = Str::slug((string) $value) . '-' . Str::slug((string) request('module'));

                    $exists = DB::table('permissions')
                        ->where('slug', $slug)
                        ->when($permissionId, fn ($query) => $query->where('id', '!=', $permissionId))
                        ->exists();

                    if ($exists) {
                        $fail('A permission with this name and module already exists.');
                    }
                },
            ],
            'module' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => 'nullable|string',
        ];
    }
}
