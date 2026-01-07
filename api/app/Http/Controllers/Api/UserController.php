<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'profile']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sort
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 5);
        $users = $query->paginate($perPage);

        return $this->success($users, 'Users retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'status' => $request->status ?? true,
        ]);

        // Assign roles
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return $this->success($user->load('roles'), 'User created successfully', 201);
    }

    public function show(User $user)
    {
        return $this->success($user->load(['roles', 'profile', 'files']), 'User retrieved successfully');
    }

    public function update(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return $this->error('You cannot update your own account through this endpoint', null, 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user->update($request->except('roles', 'password'));

        // Update roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return $this->success($user->load('roles'), 'User updated successfully');
    }

    public function destroy(Request $request, User $user)
    {
        // Prevent self-deletion
        if ($request->user()->id === $user->id) {
            return $this->error('You cannot delete your own account', null, 403);
        }

        $user->delete();

        return $this->success(null, 'User deleted successfully');
    }

    public function updateStatus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        // Prevent self-status change
        if ($request->user()->id === $user->id) {
            return $this->error('You cannot change your own status', null, 403);
        }

        $user->update(['status' => $request->status]);

        return $this->success($user, 'User status updated successfully');
    }

    public function assignRoles(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user->roles()->sync($request->roles);

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

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user->update($request->all());

        return $this->success($user->load('profile'), 'Profile updated successfully');
    }
}
