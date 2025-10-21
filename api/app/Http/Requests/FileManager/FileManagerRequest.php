<?php

namespace App\Http\Requests\FileManager;

use Illuminate\Foundation\Http\FormRequest;

class FileManagerRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
        ];

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
            'name.max' => 'Name must not exceed 255 characters',
            'file.required' => 'File is required',
            'file.file' => 'File must be a file',
            'file.max' => 'File size must not exceed 10MB',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'file' => 'uploaded file',
        ];
    }
}
