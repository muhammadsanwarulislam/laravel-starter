<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'otp'       => 'required|string|digits:6',
            'type'      => 'nullable|string|in:login,registration,password_reset,email_verification,phone_verification,email_change,phone_change,two_factor_authentication',
            'locale'    => 'nullable|string|exists:languages,code'
        ];
    }
}
