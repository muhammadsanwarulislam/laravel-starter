<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\OtpValidation;

class OtpValidationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new OtpValidation());
    }

    public function checkForRecentOtp(int $userId, string $type): ?OtpValidation
    {
        return $this->model->where('user_id', $userId)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('is_locked', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function cleanupExpiredOtps(int $userId): void
    {
        $this->model->where('user_id', $userId)
            ->where('expires_at', '<', date('Y-m-d H:i:s'))
            ->delete();
    }

    public function storeOtp(array $data): OtpValidation
    {
        $instance = [
            'user_id'       => $data['user_id'],
            'otp_code'      => $data['otp_code'],
            'expires_at'    => $data['expires_at'],
            'type'          => $data['type'],
            'ip_address'    => $data['ip_address'] ?? null,
            'attempts'      => 0,
            'is_used'       => false,
            'is_locked'     => false,
        ];

        return $this->create($instance);
    }

    public function checkOtpValidity(int $userId, int $otpCode, string $type): OtpValidation
    {
        $otpRecord = $this->findValidOtpRecord($userId, $otpCode, $type);
        
        if (!$otpRecord) {
            throw new \Exception('Invalid OTP provided');
        }
        
        $this->processOtpValidation($otpRecord);

        return $otpRecord;
    }

    private function findValidOtpRecord(int $userId, int $otpCode, string $type): ?OtpValidation
    {
        return $this->model->where('user_id', $userId)
            ->where('otp_code', $otpCode)
            ->where('type', $type)
            ->first();
    }

    private function processOtpValidation(OtpValidation $otpRecord): void
    {
        $this->validateOtpStatus($otpRecord);
        $this->updateOtpAttempt($otpRecord);
    }

    private function validateOtpStatus(OtpValidation $otpRecord): void
    {
        if ($otpRecord->is_used) {
            throw new \Exception('OTP has already been used');
        }
        
        if ($otpRecord->is_locked) {
            throw new \Exception('OTP is locked');
        }
        
        if (now()->greaterThan($otpRecord->expires_at)) {
            $otpRecord->update(['is_locked' => true]);
            throw new \Exception('OTP has expired');
        }
    }

    private function updateOtpAttempt(OtpValidation $otpRecord): void
    {
        $otpRecord->increment('attempts');
        
        if ($otpRecord->attempts > 3) {
            $otpRecord->update(['is_locked' => true]);
            throw new \Exception('Maximum OTP attempts exceeded');
        }
        
        $otpRecord->update(['is_used' => true]);
    }
}