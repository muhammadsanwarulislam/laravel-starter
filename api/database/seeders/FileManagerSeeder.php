<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FileManager;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FileManagerSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        FileManager::query()->delete();

        $users = User::all();
        $fileTypes = ['image', 'document', 'pdf', 'audio', 'video', 'archive'];
        $extensions = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'document' => ['doc', 'docx', 'txt', 'rtf'],
            'pdf' => ['pdf'],
            'audio' => ['mp3', 'wav', 'ogg'],
            'video' => ['mp4', 'avi', 'mov', 'mkv'],
            'archive' => ['zip', 'rar', '7z', 'tar'],
        ];

        $files = [];

        foreach ($users as $user) {
            // Each user gets 3-5 random files
            $fileCount = rand(3, 5);

            for ($i = 1; $i <= $fileCount; $i++) {
                $fileType = $fileTypes[array_rand($fileTypes)];
                $extension = $extensions[$fileType][array_rand($extensions[$fileType])];
                $fileName = Str::random(10) . '.' . $extension;
                $fileSize = $this->generateFileSize($fileType);

                $files[] = [
                    'user_id' => $user->id,
                    'uuid' => Str::uuid(),
                    'name' => "File {$i} - " . ucfirst($fileType) . " File",
                    'file' => $fileName,
                    'type' => $fileType,
                    'size' => $fileSize,
                    'path' => "uploads/{$user->id}/{$fileName}",
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 29)),
                ];
            }
        }

        // Insert all files
        FileManager::insert($files);

        $this->command->info('Files seeded successfully!');
        $this->command->info('Total files: ' . FileManager::count());
    }

    private function generateFileSize(string $fileType): string
    {
        return match ($fileType) {
            'image' => rand(100, 5000) . ' KB',
            'document' => rand(50, 2000) . ' KB',
            'pdf' => rand(200, 5000) . ' KB',
            'audio' => rand(1000, 10000) . ' KB',
            'video' => rand(5000, 50000) . ' KB',
            'archive' => rand(1000, 20000) . ' KB',
            default => rand(100, 5000) . ' KB',
        };
    }
}
