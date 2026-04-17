<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:155|regex:/^[A-Za-z][A-Za-z0-9]*([-_ ][A-Za-z0-9]+)*$/',
            'email'             => 'nullable|string|email|max:155|unique:users',
            'password'          => 'required|string|min:8|confirmed',
            'country_code_id'   => 'required|numeric|exists:country_codes,id',
            'phone'             => 'required|string|max:15|unique:users,phone',
            'accepted_terms'    => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'name.regex' => 'The name must start with a letter and can contain letters, numbers, spaces, hyphens, or underscores.',
            'accepted_terms.accepted' => 'You must accept the terms and conditions.',
        ];
    }
}
