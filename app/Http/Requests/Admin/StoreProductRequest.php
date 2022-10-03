<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required','max:255'],
            'sku' => ['required','unique:products,sku'],
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
