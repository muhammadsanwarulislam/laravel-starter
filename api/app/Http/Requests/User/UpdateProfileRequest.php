<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => 'sometimes|string|max:155',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:155',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'country_code_id' => 'sometimes|numeric|min:1|max:3',
            'phone' => 'nullable|string|max:20',
            'ui_locale' => 'nullable|string|max:10',
            'gender' => 'nullable|in:male,female,other',
            'type' => 'nullable|in:admin,customer,vendor,deliveryman,driver',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'country_code_id.numeric' => 'Country code must be a valid number.',
            'gender.in' => 'Gender must be one of: male, female, or other.',
            'type.in' => 'Type must be one of: admin, customer, vendor, deliveryman, or driver.',
        ];
    }
}
