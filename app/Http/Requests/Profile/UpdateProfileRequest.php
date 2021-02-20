<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        Gate::authorize('backend.profile.update');
        return true;
    }

    public function rules()
    {
        return [
            'address'           => 'required|string|max:120',
            'image'             => 'nullable|image',
            'bio'               => 'required|string|max:244'
        ];
    }
}
