<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
	public function rules(): array
	{
		return [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('permissions', 'name')->ignore($this->permission->id),
            ],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
