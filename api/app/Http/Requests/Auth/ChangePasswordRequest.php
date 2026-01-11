<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|current_password:api',
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                'min:12',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'Password must be at least 12 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
        ];
    }
}