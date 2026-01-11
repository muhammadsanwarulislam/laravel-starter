<?php
declare(strict_types=1);

namespace Database\Seeders;

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
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $editorRole = Role::where('slug', 'editor')->first();
        $userRole = Role::where('slug', 'user')->first();
        $guestRole = Role::where('slug', 'guest')->first();

        // Create users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'super@gmail.com',
                'password' => 'password123',
                'phone' => '+8801711111111',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$superAdminRole->id],
                'profile' => [
                    'gender' => 'male',
                    'type' => 'admin',
                    'address' => '123 Admin Street, Dhaka, Bangladesh',
                ],
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => 'password123',
                'phone' => '+8801722222222',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$adminRole->id],
                'profile' => [
                    'gender' => 'male',
                    'type' => 'admin',
                    'address' => '456 Admin Avenue, Dhaka, Bangladesh',
                ],
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => 'password123',
                'phone' => '+8801733333333',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$managerRole->id],
                'profile' => [
                    'gender' => 'female',
                    'type' => 'teacher',
                    'address' => '789 Manager Road, Chittagong, Bangladesh',
                ],
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'password' => 'password123',
                'phone' => '+8801744444444',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$editorRole->id],
                'profile' => [
                    'gender' => 'male',
                    'type' => 'teacher',
                    'address' => '101 Editor Lane, Sylhet, Bangladesh',
                ],
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'phone' => '+8801755555555',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$userRole->id],
                'profile' => [
                    'gender' => 'male',
                    'type' => 'student',
                    'address' => '202 User Street, Rajshahi, Bangladesh',
                ],
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'phone' => '+8801766666666',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$userRole->id],
                'profile' => [
                    'gender' => 'female',
                    'type' => 'student',
                    'address' => '303 User Avenue, Khulna, Bangladesh',
                ],
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert@example.com',
                'password' => 'password123',
                'phone' => '+8801777777777',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$userRole->id],
                'profile' => [
                    'gender' => 'male',
                    'type' => 'student',
                    'address' => '404 User Road, Barisal, Bangladesh',
                ],
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'password' => 'password123',
                'phone' => '+8801788888888',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$userRole->id],
                'profile' => [
                    'gender' => 'female',
                    'type' => 'student',
                    'address' => '505 User Lane, Rangpur, Bangladesh',
                ],
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'password' => 'password123',
                'phone' => '+8801799999999',
                'status' => false, 
                'email_verified_at' => now(),
                'roles' => [$userRole->id],
                'profile' => [
                    'gender' => 'male',
                    'type' => 'student',
                    'address' => '606 User Street, Mymensingh, Bangladesh',
                ],
            ],
            [
                'name' => 'Guest User',
                'email' => 'guest@example.com',
                'password' => 'password123',
                'phone' => '+8801700000000',
                'status' => true,
                'email_verified_at' => now(),
                'roles' => [$guestRole->id],
                'profile' => [
                    'gender' => 'other',
                    'type' => 'student',
                    'address' => '707 Guest Street, Dhaka, Bangladesh',
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
            if (in_array($user->email, ['superadmin@example.com', 'john@example.com', 'jane@example.com'])) {
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
                'superadmin@example.com' => 'সুপার অ্যাডমিন',
                'john@example.com' => 'জন ডো',
                'jane@example.com' => 'জেন স্মিথ',
                default => $user->name,
            };

            $translations[] = [
                'translatable_type' => User::class,
                'translatable_id' => $user->id,
                'language_id' => $banglaLanguage->id,
                'attribute' => 'name',
                'value' => $banglaName,
            ];
        }

        // Add Hindi translations
        if ($hindiLanguage) {
            $hindiName = match ($user->email) {
                'superadmin@example.com' => 'सुपर एडमिन',
                'john@example.com' => 'जॉन डो',
                'jane@example.com' => 'जेन स्मिथ',
                default => $user->name,
            };

            $translations[] = [
                'translatable_type' => User::class,
                'translatable_id' => $user->id,
                'language_id' => $hindiLanguage->id,
                'attribute' => 'name',
                'value' => $hindiName,
            ];
        }

        // Insert translations
        if (!empty($translations)) {
            \App\Models\Translation::insert($translations);
        }
    }
}