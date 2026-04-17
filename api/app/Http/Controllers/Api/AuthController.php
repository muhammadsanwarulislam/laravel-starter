<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Resources\Auth\AuthResource;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return $this->success([
            'user' => $result['user'],
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'locale' => $result['locale'] ?? config('app.locale'),
        ], 'Registration successful');
    }

    public function requestLoginOtp(LoginRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $credentials = [
                'phone'     => $validatedData['phone'] ?? null,
                'email'     => $validatedData['email'] ?? null,
                'password'  => $validatedData['password'],
            ];

            $result = $this->authService->initiateLoginWithOtp($credentials);

            return $this->success(new AuthResource($result), $result['message']);
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }

    public function verifyOtpAndResponse(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->authService->verifyOtp($validated, $validated['type'] ?? 'login');

            if (isset($result['error'])) {
                return $this->error($result['error'], null, $result['code']);
            }

            return $this->success([
                'user'          => $result['user'],
                'token'         => $result['token'],
                'token_type'    => 'Bearer',
                'locale'        => $result['locale'] ?? config('app.locale'),
            ], 'Login successful');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }


    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->success(null, 'Logout successful');
    }

    public function me(Request $request): JsonResponse
    {
        $result = $this->authService->getCurrentUser($request->user());

        return $this->success($result);
    }

    public function forgotPassword(Request $request): JsonResponse
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

    public function resetPassword(Request $request): JsonResponse
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

    public function changePassword(ChangePasswordRequest $request): JsonResponse
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

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $type           = $validated['type'] ?? 'login';
            $deliveryMethod = $validated['delivery_method'] ?? (!empty($validated['phone']) ? 'phone' : 'email');
            $phone          = $validated['phone'] ?? null;
            $email          = $validated['email'] ?? null;

            $result = $this->authService->resendOtp($type, $deliveryMethod, $phone, $email);

            return $this->success(['expires_at'     => $result['expires_at']], $result['message']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null);
        }
    }
}
