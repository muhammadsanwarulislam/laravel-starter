<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $rules = [
            'user_id' => 'required|exists:users,id',
            'gender' => 'required|in:male,female,other',
            'type' => 'required|in:student,teacher,admin',
            'nid' => 'nullable|string|max:50|unique:profiles,nid',
            'address' => 'nullable|string|max:500',
        ];

        // For update, ignore unique rule for current profile
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['nid'] = 'nullable|string|max:50|unique:profiles,nid,' . $this->route('profile')->id;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'The selected user does not exist',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender must be male, female, or other',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be student, teacher, or admin',
            'nid.unique' => 'This NID is already taken',
            'nid.max' => 'NID must not exceed 50 characters',
            'address.max' => 'Address must not exceed 500 characters',
        ];
    }
}
