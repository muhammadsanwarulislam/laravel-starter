<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        }

        return $user;
    }

    public function getUserWithDetails($userId): Model
    {
        return $this->userRepository->findOrFail($userId, ['roles', 'profile', 'files']);
    }

    public function updateUser(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);
        
        // Update user fields
        $updateData = array_filter($data, fn($key) => !in_array($key, ['roles', 'password']), ARRAY_FILTER_USE_KEY);
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Update roles if provided
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        // Update password if provided
        if (isset($data['password'])) {
            $this->userRepository->changePassword($user, $data['password']);
        }

        return $user;
    }

    public function deleteUser($userId): bool
    {
        return $this->userRepository->delete($this->userRepository->changeFieldType($userId));
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
        
        return $user;
    }

    public function getUserWithRoles(int $userId): ?User
    {
        return $this->userRepository->getUserWithRoles($userId);
    }
}