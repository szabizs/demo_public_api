<?php

namespace App\Http\Controllers;

use App\Contracts\BrandContract;
use App\Contracts\CategoryContract;
use App\Contracts\ProductContract;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class ProductController extends Controller
{
    private BrandRepository $brandRepository;
    private ProductContract $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(BrandRepository $brandRepository, ProductContract $productRepository, CategoryRepository $categoryRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
	{
        $products = $this->productRepository->filter(\Illuminate\Support\Facades\Request::all('search'))->get(['id', 'name', 'sku', 'brand_id', 'price', 'sale_price', 'deleted_at', 'active']);

        $products->load('brand');
        $products->load('categories');

        return Inertia::render('Admin/Products/List', [
            'filters' => \Illuminate\Support\Facades\Request::all('search'),
            'products' => $products
        ]);
	}

	public function create()
	{
        $brands = $this->brandRepository->listBrands('name', 'asc');
        $categories = $this->categoryRepository->listCategories('name', 'asc');

        return Inertia::render('Admin/Products/New', compact('brands','categories'));

        /** This is exactly the same as the above line */
//        return Inertia::render('Admin/Products/New', [
//            'brands' => $brands,
//            'categories' => $categories
//        ]);
	}

	public function store(StoreProductRequest $request)
	{
        $params = $request->except('_token');

        $this->productRepository->createProduct($params);

        return Redirect::route('admin.products.index')->with('success', 'Product has been created.');
	}

	public function edit(Product $product)
	{
        $brands = $this->brandRepository->listBrands('name', 'asc');
        $categories = $this->categoryRepository->listCategories('name', 'asc');

        $product->load(['categories','images']);

        return Inertia::render('Admin/Products/Edit', compact('product','brands','categories'));
	}

	public function update(UpdateProductRequest $request, Product $product)
	{
        $product = $this->productRepository->updateProduct($product->id, request()->except('_token'));

        return \Illuminate\Support\Facades\Redirect::route('admin.products.edit', $product)->with('success', 'Product has been successfully updated.');

    }

	public function destroy(Brand $brand)
	{
        $this->brandRepository->deleteBrand($brand->id);

        return Redirect::route('admin.brands.index')->with('success', 'Brand has been deleted.');
	}
}
