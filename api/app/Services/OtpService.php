<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Notifications\SentOtp;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Notification;
use App\Repositories\OtpValidationRepository;

class OtpService
{
    protected int $otpLength = 6;
    protected int $otpExpiryMinutes = 3;
    protected int $maxAttempts = 3;

    public function __construct(
        protected UserRepository $userRepository, 
        protected OtpValidationRepository $otpValidationRepository
        )
    {
    }

    public function createOtp(User $user, string $type = 'login', ?string $ip = null): array
    {
        $this->otpValidationRepository->cleanupExpiredOtps($user->id);

        $recentOtp = $this->otpValidationRepository->checkForRecentOtp($user->id, $type);

        if ($recentOtp) {
            return [
                'success'       => true,
                'otp'           => $recentOtp->otp_code,
                'message'       => 'OTP already sent. Please check your device.',
                'expires_at'    => $recentOtp->expires_at
            ];
        } else {
            $otp = $this->generateOtp();
    
            $expiresAt = date('Y-m-d H:i:s', strtotime('+'.$this->otpExpiryMinutes.' minutes'));
    
            $otpRecord = $this->otpValidationRepository->storeOtp([
                'user_id'       => $user->id,
                'otp_code'      => $otp,
                'expires_at'    => $expiresAt,
                'type'          => $type,
                'ip_address'    => $ip,
                'attempts'      => 0,
            ]);
        }

        return [
            'success'       => true,
            'otp'           => $otp,
            'message'       => 'OTP generated successfully',
            'expires_at'    => $expiresAt,
            'otp_id'        => $otpRecord->id
        ];
    }

    public function generateOtp(): string
    {
        return str_pad((string) random_int(0, pow(10, $this->otpLength) - 1), $this->otpLength, '0', STR_PAD_LEFT);
    }

    public function verifyOtp(int $userId, string $otp, string $type = 'login'): array
    {
       $this->otpValidationRepository->checkOtpValidity($userId, $otp, $type);    

        return [
            'success' => true,
            'message' => 'OTP verified successfully'
        ];
    }

    public function sendOtpViaEmail(User $user, string $otp): bool
    {
        try {
            Notification::send($user, new SentOtp($otp));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return false;
        }
    }

    public function sendOtpViaSms(User $user, string $otp): bool
    {
        /**
         * In a real implementation, it will integrate with an SMS gateway here to send the OTP to the user's phone number.
         */
        \Log::info("SMS OTP for {$user->phone}: {$otp}");
        Notification::send($user, new SentOtp($otp));
        return true; 
    }
}
