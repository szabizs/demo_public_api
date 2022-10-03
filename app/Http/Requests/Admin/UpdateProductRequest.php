<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required','max:255'],
            'sku' => ['required', Rule::unique('products','sku')->ignore($this->product->id)],
            'brand_id' => ['required','not_in:0','exists:brands,id'],
            'category_id' => ['required','not_in:0','exists:categories,id'],
            'price' => ['required','numeric'],
            'sale_price' => ['nullable','numeric'],
            'quantity' => ['required','numeric'],
            'description' => ['required'],
            'active' => ['boolean'],
            'featured' => ['boolean'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
