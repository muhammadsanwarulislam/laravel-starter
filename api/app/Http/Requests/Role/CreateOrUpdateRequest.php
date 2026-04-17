<?php

namespace App\Http\Requests\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:155',
                function (string $attribute, mixed $value, \Closure $fail) use ($roleId): void {
                    $slug = Str::slug((string) $value);

                    $exists = DB::table('roles')
                        ->where('slug', $slug)
                        ->when($roleId, fn ($query) => $query->where('id', '!=', $roleId))
                        ->exists();

                    if ($exists) {
                        $fail('A role with this name already exists.');
                    }
                },
            ],
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:100',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id'
        ];
    }
}
