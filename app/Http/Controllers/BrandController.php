<?php

namespace App\Http\Controllers;

use App\Contracts\BrandContract;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class BrandController extends Controller
{
    private BrandContract $brandRepository;

    public function __construct(BrandContract $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function index()
	{
        $brands = $this->brandRepository->filter(\Illuminate\Support\Facades\Request::all('search'))->get(['id', 'name', 'slug', 'logo', 'deleted_at']);
        return Inertia::render('Admin/Brands/ListBrands', [
            'filters' => \Illuminate\Support\Facades\Request::all('search'),
            'brands' => $brands
        ]);
	}

	public function create()
	{
        return Inertia::render('Admin/Brands/NewBrand');
	}

	public function store(Request $request)
	{
        $this->validate($request, [
            'name'      =>  'required|max:191',
            'logo'     => 'nullable|mimes:jpg,jpeg,png|max:1000',
        ]);

        $params = $request->except('_token');

        $this->brandRepository->createBrand($params);

        return Redirect::route('admin.brands.index')->with('success', 'Brand has been created.');
	}

	public function edit(Brand $brand)
	{
        $brand = $this->brandRepository->findBrandById($brand->id);

        return Inertia::render('Admin/Brands/Brand', [
            'brand' => $brand,
        ]);
	}

    public function deleteLogo(Brand $brand)
    {
        if($this->brandRepository->deleteLogo($brand) === true) {
            return response()->json([
                'success' => true,
                'message' => 'Brand logo has been deleted.'
            ]);
        }
    }

	public function update(Request $request, Brand $brand)
	{
        $this->validate($request, [
            'name'      =>  'required|max:191',
            'logo'     => 'nullable|mimes:jpg,jpeg,png|max:1000',
        ]);

        $brand = $this->brandRepository->updateBrand($brand->id, $request->only(['name','logo']));

        return response()->json([
            'success' => true,
            'message' => 'Brand has been updated.',
            'brand' => $brand
        ]);
	}

	public function destroy(Brand $brand)
	{
        $this->brandRepository->deleteBrand($brand->id);

        return Redirect::route('admin.brands.index')->with('success', 'Brand has been deleted.');
	}
}
