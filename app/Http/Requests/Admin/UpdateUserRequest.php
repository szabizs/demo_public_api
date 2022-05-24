<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($this->user->id)],
            'password' => ['nullable', Password::defaults()],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
