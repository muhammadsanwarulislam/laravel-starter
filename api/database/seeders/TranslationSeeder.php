<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $banglaId   = Language::where('code', 'bn')->value('id');

        $adminId    = User::where('email', 'super@gmail.com')->value('id');
        $johnId     = User::where('email', 'john@example.com')->value('id');
        $janeId     = User::where('email', 'jane@example.com')->value('id');

        $translations = [
            [
                'translatable_type' => User::class,
                'translatable_id'   => $adminId,
                'language_id'       => $banglaId,
                'attribute'         => 'name',
                'value'             => 'অ্যাডমিন ব্যবহারকারী',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'translatable_type' => User::class,
                'translatable_id'   => $johnId,
                'language_id'       => $banglaId,
                'attribute'         => 'name',
                'value'             => 'জন ডো',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'translatable_type' => User::class,
                'translatable_id'   => $janeId,
                'language_id'       => $banglaId,
                'attribute'         => 'name',
                'value'             => 'জেন স্মিথ',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                [
                    'translatable_type' => $translation['translatable_type'],
                    'translatable_id'   => $translation['translatable_id'],
                    'language_id'       => $translation['language_id'],
                    'attribute'         => $translation['attribute'],
                ],
                [
                    'value'             => $translation['value'],
                    'created_at'        => $translation['created_at'],
                    'updated_at'        => $translation['updated_at'],
                ]
            );
        }

        $this->command->info('Translations seeded successfully!');
        $this->command->info('Total translations: ' . count($translations));
    }
}