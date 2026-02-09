<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Password;
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
            'name'              => 'required|string|max:155',
            'email'             => 'required|string|email|max:155|unique:users',
            'password'          => 'required|string|min:8|confirmed',
            'country_code_id'   => 'required|numeric|min:1|max:5',
            'phone'             => 'nullable|string|max:11'
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'name.regex' => 'The name must start with a letter and can contain letters, numbers, spaces, hyphens, or underscores.'
        ];
    }
}
