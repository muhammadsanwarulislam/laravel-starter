<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Role::query()->delete();
        Permission::query()->delete();

        // Detach all pivot relationships first
        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();

        // Define modules and permissions
        $modules = [
            'users' => [
                'view' => 'View Users',
                'create' => 'Create Users',
                'edit' => 'Edit Users',
                'delete' => 'Delete Users',
                'export' => 'Export Users',
            ],
            'roles' => [
                'view' => 'View Roles',
                'create' => 'Create Roles',
                'edit' => 'Edit Roles',
                'delete' => 'Delete Roles',
                'assign' => 'Assign Roles',
            ],
            'permissions' => [
                'view' => 'View Permissions',
                'manage' => 'Manage Permissions',
            ],
            'languages' => [
                'view' => 'View Languages',
                'create' => 'Create Languages',
                'edit' => 'Edit Languages',
                'delete' => 'Delete Languages',
            ],
            'translations' => [
                'view' => 'View Translations',
                'create' => 'Create Translations',
                'edit' => 'Edit Translations',
                'delete' => 'Delete Translations',
                'import' => 'Import Translations',
            ],
            'files' => [
                'view' => 'View Files',
                'upload' => 'Upload Files',
                'delete' => 'Delete Files',
                'download' => 'Download Files',
            ],
            'settings' => [
                'view' => 'View Settings',
                'edit' => 'Edit Settings',
            ],
            'profile' => [
                'view' => 'View Profile',
                'edit' => 'Edit Profile',
            ],
        ];

        // Create permissions
        $permissions = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action => $name) {
                $slug = "{$action}-{$module}";
                $permission = Permission::create([
                    'name' => $name,
                    'slug' => $slug,
                    'module' => $module,
                    'description' => "Allows {$action} on {$module}",
                ]);
                $permissions[$slug] = $permission->id;
            }
        }

        $this->command->info('Permissions created: ' . Permission::count());

        // Create system roles
        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => 'super_admin',
                'description' => 'Has full access to all system features',
                'is_system' => true,
                'level' => 100,
                'permissions' => array_values($permissions),
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Has administrative access',
                'is_system' => true,
                'level' => 80,
                'permissions' => [
                    $permissions['view-users'],
                    $permissions['create-users'],
                    $permissions['edit-users'],
                    $permissions['view-roles'],
                    $permissions['view-permissions'],
                    $permissions['view-languages'],
                    $permissions['edit-languages'],
                    $permissions['view-translations'],
                    $permissions['edit-translations'],
                    $permissions['view-files'],
                    $permissions['upload-files'],
                    $permissions['download-files'],
                    $permissions['view-settings'],
                    $permissions['edit-settings'],
                ],
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Can manage users and content',
                'is_system' => false,
                'level' => 60,
                'permissions' => [
                    $permissions['view-users'],
                    $permissions['create-users'],
                    $permissions['edit-users'],
                    $permissions['view-files'],
                    $permissions['upload-files'],
                    $permissions['download-files'],
                ],
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Can edit content',
                'is_system' => false,
                'level' => 40,
                'permissions' => [
                    $permissions['view-files'],
                    $permissions['upload-files'],
                    $permissions['download-files'],
                ],
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular user with basic permissions',
                'is_system' => true,
                'level' => 10,
                'permissions' => [
                    $permissions['view-profile'],
                    $permissions['edit-profile'],
                    $permissions['view-files'],
                    $permissions['upload-files'],
                    $permissions['download-files'],
                ],
            ],
            [
                'name' => 'Guest',
                'slug' => 'guest',
                'description' => 'Guest user with read-only access',
                'is_system' => false,
                'level' => 1,
                'permissions' => [
                    $permissions['view-files'],
                    $permissions['download-files'],
                ],
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleData) {
            $permissionIds = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::create($roleData);
            $role->permissions()->attach($permissionIds, ['created_at' => now(), 'updated_at' => now()]);

            $this->command->info("Role '{$role->name}' created with " . count($permissionIds) . " permissions");
        }

        $this->command->info('Total roles created: ' . Role::count());
    }
}
