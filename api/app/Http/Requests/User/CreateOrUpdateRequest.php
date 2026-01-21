<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateRequest extends FormRequest
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
            'email'             => 'required|string|email|max:155|unique:users,email,' . $this->route('user'),
            'password'          => 'nullable|string|min:8|confirmed',
            'country_code_id'   => 'required|string|min:1|max:3',
            'phone'             => 'nullable|string|max:11',
            'roles'             => 'required|exists:roles,id',
            'status'            => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email has already been taken.'
        ];
    }
}
