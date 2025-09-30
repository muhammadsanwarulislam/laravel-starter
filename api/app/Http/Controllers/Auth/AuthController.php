<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth;

use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\LoginPostRequest;
use App\Http\Requests\Auth\RegistrationPostRequest;

class AuthController extends Controller
{
    use JsonResponseTrait;
    public function __construct(protected \App\Services\AuthService $authService, protected \Repository\User\UserRepository $userRepository)
    {
    }

    public function signup(RegistrationPostRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->userRegistration($request->validated());

            return $this->createdJsonResponse('User registered successfully', [
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {

            return $this->errorJsonResponse($e->getMessage());
        }
    }
    public function signin(LoginPostRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->userSignin($request->validated());

            return $this->successJsonResponse('Signin successful!', [
                'access_token'  => $data['access_token'],
                'access_type'   => 'Bearer',
                'user'          => new UserResource($data['user']),
            ]);
        } catch (\Exception $e) {
            return $this->unAuthenticatedJsonResponse($e->getMessage());
        }
    }

    public function signout(): JsonResponse
    {
        Auth::logout();
        return $this->successJsonResponse('User logged out');
    }

    public function authorizedUserInformation(): JsonResponse
    {
        try {
            $user = $this->authService->userInformation();
            
            return $this->successJsonResponse('Logged in user information', [
                'access_token'  => $user['remember_token'],
                'user'          => new UserResource($user)
            ]);
        } catch (\Exception $e) {

            return $this->unAuthenticatedJsonResponse($e->getMessage());
        }
    }
}
