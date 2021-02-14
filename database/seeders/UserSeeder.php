<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create super admin
        $adminRole = Role::where('slug','super_admin')->first();
        User::updateOrCreate([
            'role_id'       => $adminRole->id,
            'name'          => 'Super Admin',
            'email'         => 'super@gmail.com',
            'password'      => Hash::make('12345678'),
            'status'        => true
        ]);

        // Create admin
        $adminRole = Role::where('slug','admin')->first();
        User::updateOrCreate([
            'role_id'       => $adminRole->id,
            'name'          => 'Admin',
            'email'         => 'admin@gmail.com',
            'password'      => Hash::make('12345678'),
            'status'        => true
        ]);
    }
}