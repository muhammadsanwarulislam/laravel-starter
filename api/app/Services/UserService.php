<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\FileManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    public function __construct(protected UserRepository $userRepository, protected FileService $fileService)
    {
    }

    public function getFilteredUsers(
        ?string $search = null,
        ?string $role = null,
        ?string $status = null,
        string $sortField = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 5
    ): LengthAwarePaginator {
        return $this->userRepository->getFilteredUsers(
            $search,
            $role,
            $status,
            $sortField,
            $sortOrder,
            $perPage
        );
    }

    public function createUser(array $data)
    {
        $user = $this->userRepository->createUser($data);
        
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles'], true);
            $user->clearPermissionCache();
        }

        return $user;
    }

    public function getUserWithDetails($userId): Model
    {
        return $this->userRepository->findOrFail($userId, ['roles', 'profile', 'files']);
    }

    public function updateUser(int $userId, array $data): User
    {
        $user = $this->userRepository->findOrFail($userId);
        
        // Update user fields
        $updateData = array_filter($data, fn($key) => !in_array($key, ['roles', 'password']), ARRAY_FILTER_USE_KEY);
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Update roles if provided
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
            $user->clearPermissionCache();
        }

        // Update password if provided
        if (isset($data['password'])) {
            $this->userRepository->changePassword($user, $data['password']);
        }

        return $user;
    }

    public function deleteUser($userId): bool
    {
        // return $this->userRepository->delete($this->userRepository->changeFieldType($userId));
        return $this->userRepository->delete($userId);
    }

    public function updateUserStatus($userId, bool $status): User
    {
        $user = User::findOrFail($this->userRepository->changeFieldType($userId));
        $user->update(['status' => $status]);
        
        return $user;
    }

    public function assignRoles(int $userId, array $roleIds): User
    {
        $user = User::findOrFail($userId);
        $user->roles()->sync($roleIds);
        $user->clearPermissionCache();
        
        return $user;
    }

    public function getUserWithRoles(int $userId): ?User
    {
        return $this->userRepository->getUserWithRoles($userId);
    }

    public function updateOwnProfile(User $user, array $data): User
    {
        $userData = Arr::only($data, ['name', 'email', 'phone', 'country_code_id', 'ui_locale']);
        $profileData = Arr::only($data, ['gender', 'type', 'address']);

        if (!empty($userData)) {
            $user->update($userData);
        }

        if (!empty(array_filter($profileData, fn ($value) => $value !== null && $value !== ''))) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        } elseif ($user->profile && array_key_exists('address', $data)) {
            $user->profile()->update($profileData);
        }

        return $user->fresh(['profile', 'roles', 'files']);
    }

    public function updateProfilePhoto(User $user, UploadedFile $photo): User
    {
        $this->fileService->upload($user, $photo, [
            'type' => 'profile_image',
            'directory' => 'profile-photos',
            'attachable_type' => 'user',
            'attachable_id' => $user->id,
            'replace_existing' => true,
        ]);

        return $user->fresh(['profile', 'roles', 'files']);
    }
}
