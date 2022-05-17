<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
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
    public function rules()
    {
        $rules = [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required','min:5']
        ];

        if($this->isMethod('PATCH')) {
            unset($rules['email'][0]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a name',
            'name.min' => 'Please enter a name at least of :min characters',
            'email.required' => 'Please enter an email',
            'email.email' => 'The entered email address is not a valid email',
            'email.unique' => 'The entered email address is is already taken',
            'password.required' => 'Please enter a password',
            'password.min' => 'Please enter a password of at least :min characters',
        ];
    }
}
