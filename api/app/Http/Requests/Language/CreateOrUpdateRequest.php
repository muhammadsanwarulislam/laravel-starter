<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'          => 'required|string|max:10|unique:languages,code,' . $this->route('language'),
            'name'          => 'required|string|max:255',
            'native_name'   => 'nullable|string|max:255',
            'direction'     => 'required|in:ltr,rtl',
            'is_active'     => 'boolean',
            'is_default'    => 'boolean',
            'sort_order'    => 'integer|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'         => 'The language code is required.',
            'code.unique'           => 'The language code must be unique.',
            'name.required'         => 'The language name is required.',
            'direction.required'    => 'The text direction is required.',
            'direction.in'          => 'The text direction must be either "ltr" or "rtl".',
            'is_active.boolean'     => 'The is_active field must be true or false.',
            'is_default.boolean'    => 'The is_default field must be true or false.',
            'sort_order.integer'    => 'The sort order must be an integer.',
            'sort_order.min'        => 'The sort order must be at least 0.'
        ];
    }
}