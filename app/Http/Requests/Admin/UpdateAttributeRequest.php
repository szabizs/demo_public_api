<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{
    private array $types;


    public function rules(): array
    {
        $this->getEnumTypes();

        return [
            'code' => ['required','string', Rule::unique('attributes','code')->ignore($this->attribute->id)],
            'name' => ['required','string','max:255'],
            'frontend_type' => ['required', Rule::in($this->types)],
            'is_filterable' => ['required','boolean'],
            'is_required' => ['required','boolean'],
        ];
    }

    private function getEnumTypes(): void
    {
        $this->types = getEnumValues('attributes', 'frontend_type');
    }

    public function authorize(): bool
    {
        return true;
    }
}
