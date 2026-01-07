<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('slug')->get();
        return $this->success($permissions, 'Permissions retrieved successfully');
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
}
