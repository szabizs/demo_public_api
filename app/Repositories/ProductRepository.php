<?php

namespace App\Repositories;

use App\Contracts\ProductContract;
use App\Models\Product;
use App\Traits\UploadableFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository extends BaseRepository implements ProductContract
{

    use UploadableFile;

    public function __construct(Product $product)
    {
        parent::__construct($product);
        $this->model = $product; // This is the model that we are binding to

    }

    public function filterFor(array $params)
	{
		// TODO: Implement filterFor() method.
	}

	public function listProducts(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
	{
        return $this->all($columns, $order, $sort);
    }

	public function findProductById(int $id)
	{
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }
	}

	public function createProduct(array $params)
	{
        try {
            $collection = collect($params);
            $product = new Product($collection->all());
            $product->save();

            if($collection->has('category_id')) {
                $product->categories()->sync($params['category_id']);
            }

            return $product;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
	}

	public function updateProduct(int $id, array $params)
	{
        $collection = collect($params);

        $product = $this->findProductById($params['product_id']);
        $product->update($params);

        if($collection->has('category_id')) {
            $product->categories()->sync($params['category_id']);
        }

        return $product;
	}

	public function deleteProduct($id)
	{
        $product = $this->findProductById($id);

        $product->delete();

        return $product;
	}
}
