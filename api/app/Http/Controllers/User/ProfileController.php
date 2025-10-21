<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Resources\User\ProfileResource;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('limit', 10);
            $page = $request->get('offset', 1);

            $query = Profile::with(['user']);

            // Search functionality
            if ($request->has('option') && $request->get('option') === 'search') {
                $searchData = $request->get('searchData');
                $searchFields = explode(',', $request->get('searchFields', 'gender,type'));

                $query->where(function ($q) use ($searchData, $searchFields) {
                    foreach ($searchFields as $field) {
                        $q->orWhere($field, 'like', "%{$searchData}%");
                    }
                    // Search in user relation
                    $q->orWhereHas('user', function ($userQuery) use ($searchData) {
                        $userQuery->where('name', 'like', "%{$searchData}%")
                            ->orWhere('email', 'like', "%{$searchData}%");
                    });
                });
            }

            // Filter by type
            if ($request->has('type') && $request->get('type') !== 'all') {
                $query->where('type', $request->get('type'));
            }

            // Filter by gender
            if ($request->has('gender') && $request->get('gender') !== 'all') {
                $query->where('gender', $request->get('gender'));
            }

            $profiles = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'profiles' => ProfileResource::collection($profiles),
                    'pagination' => [
                        'total' => $profiles->total(),
                        'per_page' => $profiles->perPage(),
                        'current_page' => $profiles->currentPage(),
                        'last_page' => $profiles->lastPage(),
                        'from' => $profiles->firstItem(),
                        'to' => $profiles->lastItem(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profiles',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileRequest $request): JsonResponse
    {
        try {
            // Check if user already has a profile
            $existingProfile = Profile::where('user_id', $request->user_id)->first();
            if ($existingProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has a profile'
                ], 422);
            }

            $profile = Profile::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profile created successfully',
                'data' => new ProfileResource($profile->load('user'))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new ProfileResource($profile->load('user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request, Profile $profile): JsonResponse
    {
        try {
            $profile->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => new ProfileResource($profile->load('user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile): JsonResponse
    {
        try {
            $profile->delete();

            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get profile by user ID
     */
    public function getByUser($userId): JsonResponse
    {
        try {
            $profile = Profile::with('user')->where('user_id', $userId)->first();

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found for this user'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new ProfileResource($profile)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
