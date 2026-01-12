<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

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
            sortField: $request->get('sort_field', 'created_at'),
            sortOrder: $request->get('sort_order', 'desc'),
            perPage: $request->get('per_page', 5)
        );

        return $this->success($users, 'Users retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = $this->userService->createUser($validated);

        return $this->success($user->load('roles'), 'User created successfully');
    }

    public function show($id)
    {
        $user = $this->userService->getUserWithDetails($id);

        return $this->success($user, 'User retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        // Prevent self-update
        if ($request->user()->id == $id) {
            return $this->error('You cannot update your own account through this endpoint', null, 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = $this->userService->updateUser($id, $validated);

        return $this->success($user->load('roles'), 'User updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        // Prevent self-deletion
        if ($request->user()->id == $id) {
            return $this->error('You cannot delete your own account', null, 403);
        }

        $this->userService->deleteUser($id);

        return $this->success(null, 'User deleted successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        // Prevent self-status change
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
        $user = $request->user()->load(['profile', 'roles']);

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
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return $this->success($user->load('profile'), 'Profile updated successfully');
    }
}