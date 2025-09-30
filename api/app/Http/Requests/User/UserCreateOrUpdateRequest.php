<?php

namespace App\Http\Requests\User;

use Repository\Role\RoleRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Repository\User\UserRepository;

class UserCreateOrUpdateRequest extends FormRequest
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if($this->isMethod('put')) {
            $userId = $this->route(rtrim($this->userRepository::API_ENDPOINT_RESOURCE_NAME,'s'));
            $currentUser = $this->userRepository->findByID($userId);
            $incomingData = $this->all();
    
            // Compare incoming data with current user data
            if(empty($currentUser)) {
                $changedFields = [];
                foreach ($incomingData as $key => $value) {
                    if (isset($currentUser->$key) && $currentUser->$key != $value) {
                        $changedFields[$key] = $value;
                    }
                }
            }
        }

        $rules = [
            'status'  => 'nullable|in:0,1',
        ];

        if ($this->isMethod('post') || $this->isMethod('put')) {
            $rules = array_merge($rules, [
                'first_name'    => ['required', Rule::unique('users', 'first_name')->ignore($userId ?? 0)],
                'last_name'     => ['required', Rule::unique('users', 'last_name')->ignore($userId ?? 0)],
                'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($userId ?? 0)]
            ]);
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name field is required',
            'first_name.unique'   => 'The first name must be unique',
            'last_name.required'  => 'The last name field is required',
            'last_name.unique'    => 'The last name must be unique',
            'email.required'      => 'The email field is required',
            'email.unique'        => 'The email must be unique',
        ];
    }
}
