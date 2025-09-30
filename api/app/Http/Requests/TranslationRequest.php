<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('post') || $this->isMethod('put')) {
            return [
                'key' => 'required|string|max:255',
                'value' => 'required|string',
                'locale' => 'required|string|size:2',
                'group' => 'sometimes|string|max:255'
            ];
        }

        return [];
    }
}