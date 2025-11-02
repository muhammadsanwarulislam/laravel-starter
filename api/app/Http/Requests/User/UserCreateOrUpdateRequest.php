<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Repository\User\UserRepository;

class UserCreateOrUpdateRequest extends FormRequest
{
    public function __construct(protected UserRepository $userRepository) {}

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ?? $this->route('id');
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        $rules = [
            'name'              => ['required', Rule::unique('users', 'name')->ignore($userId ?? 0)],
            'email'             => ['required', 'email', Rule::unique('users', 'email')->ignore($userId ?? 0)],
            'phone'             => ['required', Rule::unique('users', 'phone')->ignore($userId ?? 0)],
            'password'          => [$isUpdate ? 'nullable' : 'required', 'min:8'],
            'status'            => 'nullable|boolean',
            'translations'      => 'sometimes|array',
            'translations.*'    => 'sometimes|array',
            'translations.*.*'  => 'sometimes|string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'     => 'The name field is required',
            'name.unique'       => 'The name must be unique',
            'email.required'    => 'The email field is required',
            'email.unique'      => 'The email must be unique',
            'phone.required'    => 'The phone field is required',
            'phone.unique'      => 'The phone must be unique',
            'password.required' => 'The password field is required',
            'password.min'      => 'The password must be at least 8 characters',
        ];
    }

    protected function prepareForValidation()
    {
        $translations = $this->input('translations', []);

        if ($this->has('name_en') || $this->has('name_bn')) {
            if ($this->has('name_en')) {
                $translations['name']['en'] = $this->input('name_en');
            }
            if ($this->has('name_bn')) {
                $translations['name']['bn'] = $this->input('name_bn');
            }
        }

        if (!$this->has('name') && isset($translations['name']['en'])) {
            $this->merge(['name' => $translations['name']['en']]);
        }

        $this->merge(['translations' => $translations]);
    }
}
