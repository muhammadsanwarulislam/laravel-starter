<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Dashboard
        $moduleAppDashboard = Module::updateOrCreate(['name' => 'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppDashboard->id,
            'name' => 'Access Dashboard',
            'slug' => 'backend.dashboard',
        ]);
        // Settings
        $moduleAppSettings = Module::updateOrCreate(['name' => 'Settings']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSettings->id,
            'name' => 'Access Settings',
            'slug' => 'backend.settings.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSettings->id,
            'name' => 'Update Settings',
            'slug' => 'backend.settings.update',
        ]);

        // Role management
        $moduleAppRole = Module::updateOrCreate(['name' => 'Role Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Access Roles',
            'slug' => 'backend.roles.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Create Role',
            'slug' => 'backend.roles.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Edit Role',
            'slug' => 'backend.roles.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Delete Role',
            'slug' => 'backend.roles.destroy',
        ]);

        // Profile
        $moduleAppProfile = Module::updateOrCreate(['name' => 'Profile']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppProfile->id,
            'name' => 'Update Profile',
            'slug' => 'backend.profile.update',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppProfile->id,
            'name' => 'Update Password',
            'slug' => 'backend.profile.password',
        ]);

        // User management(Super Admin)
        $moduleAppSuperAdmin = Module::updateOrCreate(['name' => 'Super Admin Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSuperAdmin->id,
            'name' => 'Access Super',
            'slug' => 'backend.super-admin.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSuperAdmin->id,
            'name' => 'Create Super',
            'slug' => 'backend.super-admin.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSuperAdmin->id,
            'name' => 'Edit Super',
            'slug' => 'backend.super-admin.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSuperAdmin->id,
            'name' => 'Delete Super',
            'slug' => 'backend.super-admin.destroy',
        ]);

        // User management(Admin)
        $moduleAppAdmin = Module::updateOrCreate(['name' => 'Admin Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppAdmin->id,
            'name' => 'Access Admin',
            'slug' => 'backend.admin.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppAdmin->id,
            'name' => 'Create Admin',
            'slug' => 'backend.admin.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppAdmin->id,
            'name' => 'Edit Admin',
            'slug' => 'backend.admin.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppAdmin->id,
            'name' => 'Delete Admin',
            'slug' => 'backend.admin.destroy',
        ]);

    }
}
