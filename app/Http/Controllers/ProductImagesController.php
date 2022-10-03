<?php

namespace App\Http\Controllers;

use App\Contracts\ProductContract;
use App\Models\ProductImage;
use App\Traits\UploadableFile;
use Request;

class ProductImagesController extends Controller
{
    use UploadableFile;

    protected $productRepository;

	public function __construct(ProductContract $productRepository)
    {
        $this->productRepository = $productRepository;
	}

    public function upload(\Request $request, int $product_id)
    {
        if(request()->has('image')) {

            $product = $this->productRepository->findProductById($product_id);

            $file = $this->uploadOne(request()->file('image'), 'products');

            $productImage = new ProductImage([
                'full' => $file,
//                'product_id' => request()->get('product_id'), // you may save the relation here
            ]);

            // Or alternative version
            if($product->images()->save($productImage)) {
                return response()->json([
                    'message' => 'Image has been uploaded successfully',
                    'images' => $product->images()->get(),
                ]);
            }
        }
    }

    public function deleteImage(Request $request, int $product_id, int $image_id)
    {
        $product = $this->productRepository->findProductById($product_id);

        $image = $product->images()->find($image_id);
        $url = $image->storageFull;

        if($image->delete()) {
            \Storage::disk('public')->delete($url);
            return response()->json([
                'message' => 'Image has been deleted successfully',
                'images' => $product->images()->get(),
            ]);
        }
    }

    public function setAsMain($product_id, $image_id)
    {
        $product = $this->productRepository->findProductById($product_id);

        $product->images()->update(['main' => false]);

        $image = $product->images()->find($image_id);

        if($image->update(['main' => true])) {
            return response()->json([
                'message' => 'Image has been set as main successfully',
                'images' => $product->images()->get(),
            ]);
        }

    }
}
