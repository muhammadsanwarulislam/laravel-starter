<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateOrUpdateRequest;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(Request $request)
    {
        $users = $this->userService->getFilteredUsers(
            search: $request->search,
            role: $request->role,
            status: $request->status,
            sortField: $request->input('sort_field', 'created_at'),
            sortOrder: $request->input('sort_order', 'desc'),
            perPage: (int)$request->input('limit', 5)
        );

        return $this->success($users, 'Users retrieved successfully');
    }

    public function store(CreateOrUpdateRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return $this->success($user->load('roles'), 'User created successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->getUserWithDetails($id);
            return $this->success($user, 'User retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function update(CreateOrUpdateRequest $request, $id)
    {
        try {
            $user = $this->userService->updateUser((int)$id, $request->validated());
            return $this->success($user->load('roles'), 'User updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            if ($request->user()->id == $id) {
                return $this->error('You cannot delete your own account', null, 403);
            }

            $this->userService->deleteUser($id);

            return $this->success(null, 'User deleted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->id == $id) {
            return $this->error('You cannot change your own status', null, 403);
        }

        $validated = $request->validate([
            'status' => 'required|boolean'
        ]);

        $user = $this->userService->updateUserStatus($id, $validated['status']);

        return $this->success($user, 'User status updated successfully');
    }

    public function assignRoles(Request $request, $id)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = $this->userService->assignRoles($id, $validated['roles']);

        return $this->success($user->load('roles'), 'Roles assigned successfully');
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load(['profile', 'roles', 'files']);

        return $this->success([
            'user' => $user,
            'permissions' => $user->cachedPermissions()
        ], 'Profile retrieved successfully');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'country_code_id' => 'nullable|exists:countries,id',
            'ui_locale' => 'nullable|exists:languages,code',
            'gender' => 'nullable|in:male,female,other',
            'type' => 'nullable|in:student,teacher,admin',
            'address' => 'nullable|string|max:500',
        ]);

        $updatedUser = $this->userService->updateOwnProfile($user, $validated);

        return $this->success([
            'user' => $updatedUser->load(['profile', 'roles', 'files']),
            'permissions' => $updatedUser->cachedPermissions(),
        ], 'Profile updated successfully');
    }

    public function updateProfilePhoto(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $updatedUser = $this->userService->updateProfilePhoto($request->user(), $validated['photo']);

        return $this->success([
            'user' => $updatedUser->load(['profile', 'roles', 'files']),
            'permissions' => $updatedUser->cachedPermissions(),
        ], 'Profile photo updated successfully');
    }
}
