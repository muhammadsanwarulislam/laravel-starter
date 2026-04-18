<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountryCodeSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            LanguageSeeder::class,
            UiTranslationSeeder::class,
            ContentTranslationSeeder::class,
            FileManagerSeeder::class,
        ]);

        $this->command->info('===============================================');
        $this->command->info('    Database seeded successfully!');
        $this->command->info('===============================================');
        $this->command->info('Default Login Credentials:');
        $this->command->info('================================================');
        $this->command->info('  Email: super@gmail.com');
        $this->command->info('  Password: password');
        $this->command->info('---------------------------------------------------');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@example.com');
        $this->command->info('  Password: password');
        $this->command->info('---------------------------------------------------');
        $this->command->info('Regular User:');
        $this->command->info('  Email: guest@example.com');
        $this->command->info('  Password: password');
        $this->command->info('===============================================');
    }
}
