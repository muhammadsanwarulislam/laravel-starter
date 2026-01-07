<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return $this->success([
            'user' => $result['user'],
            'token' => $result['token'],
            'token_type' => 'Bearer',
        ], 'Registration successful', 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $locale = $request->input('locale');

        $result = $this->authService->login($credentials, $locale);

        if (isset($result['error'])) {
            return $this->error($result['error'], null, $result['code']);
        }

        return $this->success([
            'user' => $result['user'],
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'locale' => $result['locale'],
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success(null, 'Logout successful');
    }

    public function me(Request $request)
    {
        $result = $this->authService->getCurrentUser($request->user());

        return $this->success($result);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $result = $this->authService->forgotPassword($request->email);

        if ($result['success']) {
            return $this->success(null, $result['message']);
        }

        return $this->error($result['message'], null, 400);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $result = $this->authService->resetPassword($request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ));

        if ($result['success']) {
            return $this->success(null, $result['message']);
        }

        return $this->error($result['message'], null, 400);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->authService->changePassword(
            $request->user(),
            $request->validated()
        );

        if (isset($result['error'])) {
            return $this->error($result['error'], null, $result['code']);
        }

        return $this->success(null, $result['message']);
    }
}
