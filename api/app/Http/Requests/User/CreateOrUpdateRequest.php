<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        $isCreating = $this->isMethod('POST');

        return [
            'name' => ($isCreating ? 'required' : 'sometimes') . '|string|max:155',
            'email' => [
                ($isCreating ? 'required' : 'sometimes'),
                'string',
                'email',
                'max:155',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'country_code_id' => ($isCreating ? 'required' : 'sometimes') . '|numeric|min:1|max:3',
            'phone' => 'nullable|string|max:20',
            'status' => ($isCreating ? 'required' : 'sometimes') . '|boolean',
            'roles' => ($isCreating ? 'required' : 'sometimes') . '|array',
            'roles.*' => 'exists:roles,id',
            'password' => $isCreating
                ? 'required|string|min:8|confirmed'
                : 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'roles.required' => 'At least one role is required.',
            'roles.array' => 'Roles must be an array.',
            'roles.*.exists' => 'One or more selected roles are invalid.',
            'password.required' => 'Password is required when creating a user.',
        ];
    }


    protected function prepareForValidation()
    {
        if ($this->has('roles') && !is_array($this->roles)) {
            $this->merge(['roles' => [$this->roles]]);
        }
    }
}
