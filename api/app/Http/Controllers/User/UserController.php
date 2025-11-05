<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\UserCreateOrUpdateRequest;

class UserController extends Controller
{
    use JsonResponseTrait;

    public function __construct(protected UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $this->userService->getUsers($request);
            return $this->successJsonResponse('User list', UserResource::collection($data['users']));
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateOrUpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = $this->userService->createUser($validatedData);

            return $this->createdJsonResponse(
                'User created successfully',
                ['user' => new UserResource($user)]
            );
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);

            return $this->successJsonResponse(
                "User details retrieved successfully",
                new UserResource($user)
            );
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCreateOrUpdateRequest $request, string $id)
    {
        try {
            $isPatch = $request->isMethod('patch');
            $validatedData = $request->validated();

            $user = $this->userService->updateUser($validatedData, $id, $isPatch);

            $successMessage = $isPatch ? 'User status updated successfully' : 'User updated successfully';

            return $this->successJsonResponse(
                $successMessage,
                ['user' => new UserResource($user)]
            );
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUserById($id);

            return $this->successJsonResponse('User deleted successfully');
        } catch (\Exception $e) {
            return $this->errorJsonResponse($e->getMessage());
        }
    }
}
