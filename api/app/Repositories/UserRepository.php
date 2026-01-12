<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function getFilteredUsers(
        ?string $search = null,
        ?string $role = null,
        ?string $status = null,
        string $sortField = 'created_at',
        string $sortOrder = 'desc',
        int $perPage = 5
    ): LengthAwarePaginator {
        $query = $this->model->with(['roles', 'profile']);

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('slug', $role);
            });
        }

        // Filter by status
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Sort
        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($perPage);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['status'] = true;

        return $this->create($data);
    }

    public function updateUser(int $userId, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->update($userId, $data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findActiveUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email, 'status' => true]);
    }

    public function assignDefaultRole(User $user, string $roleSlug = 'user'): void
    {
        $userRole = \App\Models\Role::where('slug', $roleSlug)->first();
        if ($userRole) {
            $user->roles()->sync([$userRole->id => [
                'created_at' => now(),
                'updated_at' => now()
            ]]);
        }
    }

    public function updateUserLocale(User $user, string $locale): bool
    {
        return $this->update($user->id, ['ui_locale' => $locale]);
    }

    public function getUserWithRoles(int $userId): ?User
    {
        return $this->find($userId, ['roles']);
    }

    public function verifyPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    public function changePassword(User $user, string $newPassword): bool
    {
        return $this->update($user->id, [
            'password' => Hash::make($newPassword)
        ]);
    }
}
