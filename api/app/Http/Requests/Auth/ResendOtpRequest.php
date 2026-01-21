<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'              => 'nullable|string|in:login,registration,password_reset,email_verification,phone_verification,email_change,phone_change,two_factor_authentication',
            'delivery_method'   => 'nullable|string|in:email,phone',
            'phone'             => 'nullable|string|max:15',
            'email'             => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required_if' => 'Phone number is required when delivery method is phone',
            'email.required_if' => 'Email is required when delivery method is email',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            
            // Check if at least one identifier is provided
            if (empty($data['phone']) && empty($data['email'])) {
                $validator->errors()->add('identifier', 'Either phone or email must be provided.');
            }
            
            // Check if delivery method matches the provided identifier
            if ($data['delivery_method'] === 'phone' && empty($data['phone'])) {
                $validator->errors()->add('phone', 'Phone number is required for phone delivery method.');
            }
            
            if ($data['delivery_method'] === 'email' && empty($data['email'])) {
                $validator->errors()->add('email', 'Email is required for email delivery method.');
            }

            // Warn if both are provided but suggest using one
            if (!empty($data['phone']) || !empty($data['email'])) {

                if ($data['delivery_method'] === 'phone') {
                    $this->merge(['email' => null]);
                } elseif ($data['delivery_method'] === 'email') {
                    $this->merge(['phone' => null]);
                }
            }
        });
    }
}