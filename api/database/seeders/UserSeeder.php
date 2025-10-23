<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Super Admin',
                'email'             => 'super@gmail.com',
                'phone'             => '1774445555',
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'John Doe',
                'email'             => 'john@example.com',
                'phone'             => '1885556666',
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Jane Smith',
                'email'             => 'jane@example.com',
                'phone'             => '1996667777',
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Mark Johnson',
                'email'             => 'mark@example.com',
                'phone'             => '2007778888',
                'email_verified_at' => now(),
                'password'          => 'password',
                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name'              => $user['name'],
                    'phone'             => $user['phone'],
                    'email_verified_at' => $user['email_verified_at'],
                    'password'          => $user['password'],
                    'remember_token'    => $user['remember_token'],
                    'created_at'        => $user['created_at'],
                    'updated_at'        => $user['updated_at'],
                ]
            );

            Profile::updateOrCreate(
                ['user_id' => User::where('email', $user['email'])->value('id')],
                [
                    'user_id' => User::where('email', $user['email'])->value('id'),
                    'gender' => 'male',
                    'type' => 'student',
                    'nid' => '1234567890',
                    'address' => '123 Main St, Anytown, USA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('User table seeded!');
        $this->command->info('Total users: ' . count($users));
    }
}
