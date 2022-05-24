<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
	public function rules(): array
	{
		return [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('roles', 'name'),
            ],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
