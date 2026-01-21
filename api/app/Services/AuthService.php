<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Services\LocalizationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected OtpService $otpService,
        protected LocalizationService $localizationService
    ) {}

    public function register(array $data): array
    {
        DB::beginTransaction();
        
        try {
            $user = $this->userRepository->createUser($data);
            $this->userRepository->assignDefaultRole($user);

            event(new Registered($user));

            $accessToken = $this->createAccessToken($user);

            $deliveryMethod = $user->phone ? 'phone' : 'email';
            $this->generateAndSendOtp($user, 'registration', $deliveryMethod);

            DB::commit();
            return [
                'user' => $user,
                'token' => $accessToken
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Registration failed: ' . $e->getMessage());
        }
    }

    public function initiateLoginWithOtp(array $credentials): array
    {
        $phone = $credentials['phone'] ?? null;
        $email = $credentials['email'] ?? null;
        $password = $credentials['password'];

        $user = $this->findUserByIdentifier($phone, $email);

        if (!$this->validateUserCredentials($user, $password)) {
            throw new \Exception('Invalid credentials provided');
        }

        if (!$user->isActive()) {
            throw new \Exception('User account is inactive');
        }

        $deliveryMethod = $phone ? 'phone' : 'email';
        $otpResult = $this->generateAndSendOtp($user, 'login', $deliveryMethod);

        if (!$otpResult['success']) {
            throw new \Exception('Failed to generate or send OTP: ' . $otpResult['message']);
        }

        $temporaryToken = $user->createToken('otp_verification_token')->plainTextToken;

        return $this->buildOtpSentResponse([
            'message'           => $otpResult['message'],
            'identifier'        => $phone ?? $email,
            'identifier_type'   => $phone ? 'phone' : 'email',
            'expires_at'        => $otpResult['expires_at'],
            'temporary_token'   => $temporaryToken,
        ]);
    }

    private function findUserByIdentifier(?string $phone, ?string $email): ?User
    {
        return $phone
            ? $this->userRepository->findByPhone($phone)
            : $this->userRepository->findByEmail($email);
    }


    private function validateUserCredentials(?User $user, string $password): bool
    {
        return $user && Hash::check($password, $user->password);
    }


    private function generateAndSendOtp(User $user, ?string $type = null, ?string $deliveryMethod = null): array
    {
        $otpResult = $this->otpService->createOtp($user, $type, request()->ip());

        if (!$otpResult['success']) {
            return $otpResult;
        }

        $this->sendOtpToUser($user, $otpResult['otp'], $deliveryMethod);

        return $otpResult;
    }


    private function sendOtpToUser(User $user, string $otp, string $method): bool
    {
        return $method === 'phone'
            ? $this->otpService->sendOtpViaSms($user, $otp)
            : $this->otpService->sendOtpViaEmail($user, $otp);
    }


    public function verifyOtp($validateData, ?string $type = null): array
    {
        $user = $this->userRepository->findOrFail(auth()->user()->id);
        $otp = $validateData['otp'];

        $this->otpService->verifyOtp($user->id, $otp, $type);

        $accessToken = $this->createAccessToken($user);

        return $this->buildLoginSuccessResponse($user, $accessToken);
    }

    private function createAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }


    private function buildOtpSentResponse(array $data): array
    {
        return [
            'message'           => $data['message'],
            'otp_required'      => true,
            'identifier'        => $data['identifier'],
            'identifier_type'   => $data['identifier_type'],
            'expires_at'        => $data['expires_at'],
            'temporary_token'   => $data['temporary_token'],
            'token_type'        => 'Bearer',
        ];
    }

    private function buildLoginSuccessResponse(User $user, string $token): array
    {
        return [
            'user' => $user->load('roles.permissions'),
            'token' => $token,
            'token_type' => 'Bearer',
            'locale' => $this->localizationService->getCurrentLocale(),
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function getCurrentUser(User $user): array
    {
        $user->load(['roles.permissions', 'profile']);

        return [
            'user'          => $user,
            'permissions'   => $user->cachedPermissions(),
            'locale'        => $this->localizationService->getCurrentLocale(),
            'locales'       => $this->localizationService->getAvailableLocales()
        ];
    }

    public function forgotPassword(string $email): array
    {
        $status = Password::sendResetLink(['email' => $email]);

        return [
            'success' => $status === Password::RESET_LINK_SENT,
            'message' => __($status)
        ];
    }

    public function resetPassword(array $data): array
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return [
            'success' => $status === Password::PASSWORD_RESET,
            'message' => __($status)
        ];
    }

    public function changePassword(User $user, array $data): array
    {
        if (!$this->userRepository->verifyPassword($user, $data['current_password'])) {
            return ['error' => 'Current password is incorrect', 'code' => 422];
        }

        $this->userRepository->changePassword($user, $data['password']);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }

    public function resendOtp(string $type = 'login', ?string $deliveryMethod = null, ?string $phone = null, ?string $email = null): array
    {
        $user = $this->findUserByIdentifier($phone, $email);

        $otpResult = $this->generateAndSendOtp($user, $type, $deliveryMethod);

        if (!$otpResult['success']) {
            throw new \Exception('Failed to generate or send OTP: ' . $otpResult['message']);
        }

        return [
            'message'           => $otpResult['message'],
            'expires_at'        => $otpResult['expires_at'],
        ];
    }
}
