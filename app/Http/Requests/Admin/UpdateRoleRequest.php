<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
	public function rules(): array
	{
		return [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('roles', 'name')->ignore($this->role->id),
            ],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
