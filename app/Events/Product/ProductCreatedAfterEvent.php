<?php

namespace App\Events\Product;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreatedAfterEvent
{
    use SerializesModels;
	use Dispatchable;

    private Product $product;

    public function __construct(Product $product)
	{
        $this->product = $product;
	}

    public function getProduct()
    {
        return $this->product;
    }
}
