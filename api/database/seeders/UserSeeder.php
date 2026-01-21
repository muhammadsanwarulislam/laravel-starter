<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CountryCode;
use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        User::query()->delete();
        Profile::query()->delete();

        // Get roles
        $superAdminRole     = Role::where('slug', 'super_admin')->first();
        $adminRole          = Role::where('slug', 'admin')->first();
        $managerRole        = Role::where('slug', 'manager')->first();
        $editorRole         = Role::where('slug', 'editor')->first();
        $userRole           = Role::where('slug', 'user')->first();
        $guestRole          = Role::where('slug', 'guest')->first();

        $bangladeshiCountryCode = CountryCode::where('code', 'BD')->first();

        // Create users
        $users = [
            [
                'name'              => 'Super Admin',
                'email'             => 'super@gmail.com',
                'password'          => 'password',
                'country_code_id'   => $bangladeshiCountryCode->id,
                'phone'             => '01711111111',
                'status'            => true,
                'email_verified_at' => now(),
                'roles'             => [$superAdminRole->id],
                'profile' => [
                    'gender'    => 'male',
                    'type'      => 'admin',
                    'address'   => '123 Admin Street, Dhaka, Bangladesh',
                ],
            ],
            [
                'name'              => 'Administrator',
                'email'             => 'admin@example.com',
                'password'          => 'password',
                'country_code_id'   => $bangladeshiCountryCode->id,
                'phone'             => '01722222222',
                'status'            => true,
                'email_verified_at' => now(),
                'roles'             => [$adminRole->id],
                'profile' => [
                    'gender'    => 'male',
                    'type'      => 'admin',
                    'address'   => '456 Admin Avenue, Dhaka, Bangladesh',
                ],
            ],
            [
                'name'              => 'Manager User',
                'email'             => 'manager@example.com',
                'password'          => 'password',
                'country_code_id'   => $bangladeshiCountryCode->id,
                'phone'             => '01733333333',
                'status'            => true,
                'email_verified_at' => now(),
                'roles'             => [$managerRole->id],
                'profile' => [
                    'gender'    => 'female',
                    'type'      => 'teacher',
                    'address'   => '789 Manager Road, Chittagong, Bangladesh',
                ],
            ],
            [
                'name'              => 'Editor User',
                'email'             => 'editor@example.com',
                'password'          => 'password',
                'country_code_id'   => $bangladeshiCountryCode->id,
                'phone'             => '01744444444',
                'status'            => true,
                'email_verified_at' => now(),
                'roles'             => [$editorRole->id],
                'profile' => [
                    'gender'    => 'male',
                    'type'      => 'teacher',
                    'address'   => '101 Editor Lane, Sylhet, Bangladesh',
                ],
            ],
            [
                'name'              => 'Guest User',
                'email'             => 'guest@example.com',
                'password'          => 'password',
                'country_code_id'   => $bangladeshiCountryCode->id,
                'phone'             => '01700000000',
                'status'            => true,
                'email_verified_at' => now(),
                'roles'             => [$guestRole->id],
                'profile' => [
                    'gender'    => 'other',
                    'type'      => 'student',
                    'address'   => '707 Guest Street, Dhaka, Bangladesh',
                ],
            ],
        ];

        foreach ($users as $userData) {
            $profileData = $userData['profile'];
            $roleIds = $userData['roles'];
            unset($userData['profile'], $userData['roles']);

            // Create user
            $user = User::create([
                ...$userData,
                'password' => Hash::make($userData['password']),
            ]);

            // Create profile
            Profile::create([
                'user_id' => $user->id,
                ...$profileData,
            ]);

            // Assign roles
            $user->roles()->attach($roleIds, ['created_at' => now(), 'updated_at' => now()]);

            // Add translations for user names (example)
            if (in_array($user->email, ['super@gmail.com', 'admin@example.com', 'manager@example.com', 'editor@example.com', 'guest@example.com'])) {
                $this->addUserTranslations($user);
            }
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Total users: ' . User::count());
    }

    private function addUserTranslations(User $user): void
    {
        $banglaLanguage = \App\Models\Language::where('code', 'bn')->first();
        $hindiLanguage = \App\Models\Language::where('code', 'hi')->first();

        $translations = [];

        // Add Bengali translations
        if ($banglaLanguage) {
            $banglaName = match ($user->email) {
                'super@gmail.com'       => 'সুপার অ্যাডমিন',
                'admin@example.com'     => 'অ্যাডমিন',
                'manager@example.com'   => 'ম্যানেজার',
                'editor@example.com'    => 'এডিটর',
                'guest@example.com'     => 'গেস্ট',
                default => $user->name,
            };

            $translations[] = [
                'translatable_type' => User::class,
                'translatable_id'   => $user->id,
                'language_id'       => $banglaLanguage->id,
                'attribute'         => 'name',
                'value'             => $banglaName,
            ];
        }

        // Add Hindi translations
        if ($hindiLanguage) {
            $hindiName = match ($user->email) {
                'super@gmail.com'       => 'सुपर एडमिन',
                'admin@example.com'     => 'एडमिन',
                'manager@example.com'   => 'मैनेजर',
                'editor@example.com'    => 'एडिटर',
                'guest@example.com'     => 'गेस्ट',
                default => $user->name,
            };

            $translations[] = [
                'translatable_type'     => User::class,
                'translatable_id'       => $user->id,
                'language_id'           => $hindiLanguage->id,
                'attribute'             => 'name',
                'value'                 => $hindiName,
            ];
        }

        // Insert translations
        if (!empty($translations)) {
            \App\Models\Translation::insert($translations);
        }
    }
}
