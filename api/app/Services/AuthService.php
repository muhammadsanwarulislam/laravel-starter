<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Services\LocalizationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected UserRepository $userRepository;
    protected LocalizationService $localizationService;

    public function __construct(
        UserRepository $userRepository,
        LocalizationService $localizationService
    ) {
        $this->userRepository = $userRepository;
        $this->localizationService = $localizationService;
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->createUser($data);
        $this->userRepository->assignDefaultRole($user);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $credentials, ?string $locale = null): array
    {
        if (!Auth::attempt($credentials)) {
            return ['error' => 'Invalid credentials', 'code' => 401];
        }

        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !$user->isActive()) {
            return ['error' => 'Your account is inactive', 'code' => 403];
        }

        if ($locale) {
            $this->userRepository->updateUserLocale($user, $locale);
            $this->localizationService->setLocale($locale);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('roles.permissions'),
            'token' => $token,
            'locale' => $this->localizationService->getCurrentLocale()
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
            'user' => $user,
            'permissions' => $user->cachedPermissions(),
            'locale' => $this->localizationService->getCurrentLocale(),
            'locales' => $this->localizationService->getAvailableLocales()
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
}
