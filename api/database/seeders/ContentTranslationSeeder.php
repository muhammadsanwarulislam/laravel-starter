<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Translation;
use App\Models\User;
use App\Models\Language;
use Illuminate\Database\Seeder;

class ContentTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding content translations...');

        // Only translate these users
        $users = User::whereIn('email', [
            'super@gmail.com',
            'admin@example.com',
            'john@example.com',
            'jane@example.com'
        ])->get();

        // Only use these languages
        $languages = Language::whereIn('code', ['bn', 'hi'])->get();

        $translationData = [];

        foreach ($users as $user) {
            foreach ($languages as $language) {
                // Add translation entry
                $translationData[] = [
                    'translatable_type' => User::class,
                    'translatable_id' => $user->id,
                    'language_id' => $language->id,
                    'attribute' => 'name',
                    'value' => $this->translateName($user->name, $language->code),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert all at once
        if (!empty($translationData)) {
            Translation::insert($translationData);
            $this->command->info('Successfully seeded ' . count($translationData) . ' translations.');
        } else {
            $this->command->warn('No translation data to seed.');
        }
    }

    private function translateName(string $name, string $locale): string
    {
        $translations = [
            'Super Admin' => ['bn' => 'সুপার অ্যাডমিন', 'hi' => 'सुपर एडमिन'],
            'Administrator' => ['bn' => 'অ্যাডমিনিস্ট্রেটর', 'hi' => 'प्रशासक'],
            'John Doe' => ['bn' => 'জন ডো', 'hi' => 'जॉन डो'],
            'Jane Smith' => ['bn' => 'জেন স্মিথ', 'hi' => 'जेन स्मिथ'],
        ];

        return $translations[$name][$locale] ?? $name;
    }
}
