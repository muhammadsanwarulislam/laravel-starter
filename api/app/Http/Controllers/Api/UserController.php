<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateOrUpdateRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

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

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            // Update user table fields
            $userFields = ['name', 'email', 'phone', 'country_code_id', 'ui_locale'];
            foreach ($userFields as $field) {
                if (isset($validated[$field])) {
                    $user->$field = $validated[$field];
                }
            }
            $user->save();

            // Update profile table fields
            $profileFields = ['gender', 'type', 'address'];
            $profileData = [];
            foreach ($profileFields as $field) {
                if (isset($validated[$field])) {
                    $profileData[$field] = $validated[$field];
                }
            }

            if (!empty($profileData)) {
                if ($user->profile) {
                    $user->profile->update($profileData);
                } else {
                    $user->profile()->create($profileData);
                }
            }

            $user->load(['profile', 'roles', 'files']);

            return $this->success([
                'user' => $user,
                'permissions' => $user->cachedPermissions()
            ], 'Profile updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }
}
