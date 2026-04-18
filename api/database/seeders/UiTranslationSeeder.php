<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\UiTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UiTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/translations');

        foreach (File::files($path) as $file) {
            $langCode = $file->getFilenameWithoutExtension();
            $language = Language::where('code', $langCode)->first();

            if (!$language) {
                $this->command->warn("Language '$langCode' not found, skipping.");
                continue;
            }

            $translations = json_decode(File::get($file), true);
            if (!is_array($translations)) {
                $this->command->error("Invalid JSON in {$file->getFilename()}");
                continue;
            }

            foreach ($translations as $key => $value) {
                UiTranslation::updateOrCreate(
                    [
                        'group'       => 'ui',
                        'key'         => $key,
                        'language_id' => $language->id,
                    ],
                    ['value' => $value]
                );
            }

            $this->command->info("Imported " . count($translations) . " translations for $langCode");
        }
    }
}
