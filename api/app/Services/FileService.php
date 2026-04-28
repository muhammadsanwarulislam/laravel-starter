<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\FileManager;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileService
{
    public function upload(User $actor, UploadedFile $uploadedFile, array $options = []): FileManager
    {
        $attachableAlias = $options['attachable_type'] ?? null;
        $attachableId = isset($options['attachable_id']) ? (int) $options['attachable_id'] : null;
        $attachable = $this->resolveAttachable($attachableAlias, $attachableId);
        $ownerId = $this->resolveOwnerId($actor, $attachable);

        $this->authorizeAttachment($actor, $attachableAlias, $attachable);

        $type = $options['type'] ?? 'file';
        $directory = $this->resolveDirectory($options, $type, $attachableAlias);
        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName ?: 'file', '_');
        $timestamp = now()->format('Ymd_His');
        $random = Str::lower(Str::random(8));
        $extension = strtolower($uploadedFile->getClientOriginalExtension() ?: $uploadedFile->extension() ?: 'bin');
        $filename = "{$timestamp}_{$random}_{$safeName}.{$extension}";
        $relativePath = trim($directory, '/') . '/' . now()->format('Y/m/d') . '/' . $filename;

        if (!empty($options['replace_existing'])) {
            $this->deleteExistingMatches(
                userId: $ownerId,
                type: $type,
                attachable: $attachable
            );
        }

        $targetDirectory = public_path(dirname($relativePath));

        if (!File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $fileSize = $uploadedFile->getSize();
        $uploadedFile->move($targetDirectory, basename($relativePath));

        return FileManager::create([
            'user_id' => $ownerId,
            'uuid' => (string) Str::uuid(),
            'name' => $originalName ?: $safeName,
            'file' => $filename,
            'type' => $type,
            'size' => (string) $fileSize,
            'path' => $relativePath,
            'fileable_id' => $attachable?->getKey(),
            'fileable_type' => $attachable?->getMorphClass(),
        ])->fresh();
    }

    public function listForUser(User $actor, array $filters = [])
    {
        $ownerId = $actor->id;
        $attachable = null;

        if (!empty($filters['type'])) {
            // Applied after the attachable branch initializes the query.
        }

        if (!empty($filters['attachable_type'])) {
            $attachable = $this->resolveAttachable(
                $filters['attachable_type'],
                isset($filters['attachable_id']) ? (int) $filters['attachable_id'] : null
            );

            $this->authorizeAttachment($actor, $filters['attachable_type'], $attachable, true);
            $ownerId = $this->resolveOwnerId($actor, $attachable);
        }

        $query = FileManager::query()
            ->latest('id')
            ->where('user_id', $ownerId);

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if ($attachable) {
            $query->where('fileable_type', $attachable->getMorphClass())
                ->where('fileable_id', $attachable->getKey());
        }

        return $query->paginate((int) ($filters['limit'] ?? 20));
    }

    public function findByToken(string $token): FileManager
    {
        $normalized = strtr($token, '-_', '+/');
        $padding = strlen($normalized) % 4;

        if ($padding > 0) {
            $normalized .= str_repeat('=', 4 - $padding);
        }

        $decoded = base64_decode($normalized, true);

        if ($decoded === false || $decoded === '') {
            abort(404, 'File not found.');
        }

        return FileManager::query()->where('uuid', $decoded)->firstOrFail();
    }

    protected function resolveDirectory(array $options, string $type, ?string $attachableAlias): string
    {
        if (!empty($options['directory'])) {
            $segments = collect(explode('/', trim((string) $options['directory'], '/')))
                ->filter()
                ->map(fn (string $segment) => Str::slug($segment, '-'))
                ->filter()
                ->values()
                ->all();

            return 'uploads/' . implode('/', $segments);
        }

        if ($type === 'profile_image') {
            return 'uploads/profile-photos';
        }

        return 'uploads/files';
    }

    protected function resolveAttachable(?string $attachableAlias, ?int $attachableId): ?Model
    {
        if (!$attachableAlias || !$attachableId) {
            return null;
        }

        return match ($attachableAlias) {
            'user' => User::query()->findOrFail($attachableId),
            default => null,
        };
    }

    protected function authorizeAttachment(User $actor, ?string $attachableAlias, ?Model $attachable, bool $isReadOnly = false): void
    {
        if ($attachableAlias === 'user' && $attachable instanceof User) {
            if ($actor->id === $attachable->id || $actor->isSuperAdmin()) {
                return;
            }

            $requiredPermission = $isReadOnly ? 'view-users' : 'edit-users';

            if (!$actor->hasPermission($requiredPermission)) {
                throw new AuthorizationException('You are not allowed to manage files for this user.');
            }

            return;
        }
    }

    protected function resolveOwnerId(User $actor, ?Model $attachable): int
    {
        if ($attachable instanceof User) {
            return $attachable->id;
        }

        return $actor->id;
    }

    protected function deleteExistingMatches(int $userId, string $type, ?Model $attachable = null): void
    {
        FileManager::query()
            ->where('user_id', $userId)
            ->where('type', $type)
            ->when(
                $attachable,
                fn (Builder $query) => $query
                    ->where('fileable_type', $attachable->getMorphClass())
                    ->where('fileable_id', $attachable->getKey())
            )
            ->get()
            ->each(function (FileManager $file): void {
                $path = public_path($file->getRawOriginal('path'));

                if (File::exists($path)) {
                    File::delete($path);
                }

                $file->delete();
            });
    }
}
