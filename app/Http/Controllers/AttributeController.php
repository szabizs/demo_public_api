<?php

namespace App\Http\Controllers;

use App\Contracts\AttributeContract;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\UpdateAttributeRequest;
use App\Models\Attribute;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{

    private AttributeContract $attributeRepository;

    public function __construct(AttributeContract $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    public function index()
	{
        $attributes = $this->attributeRepository->filter(Request::all('search', 'trashed'))->get(['id', 'name', 'frontend_type', 'is_filterable', 'is_required', 'deleted_at']);

        return Inertia::render('Admin/Attributes/ListAttributes', [
            'filters' => Request::all('search', 'trashed'),
            'attributes' => $attributes
        ]);
	}

	public function create()
	{
        $types = getEnumValues('attributes', 'frontend_type');

        return Inertia::render('Admin/Attributes/NewAttribute', [
            'types' => $types
        ]);
	}

	public function store(StoreAttributeRequest $request)
	{
        $this->attributeRepository->createAttribute($request->only(['code','name','frontend_type','is_filterable','is_required']));

        return Redirect::route('admin.attributes.index')->with('success', 'Attribute has been created.');

    }

	public function edit(Attribute $attribute)
	{
        $attribute = $this->attributeRepository->findAttributeById($attribute->id);

        $types = getEnumValues('attributes', 'frontend_type');

        return Inertia::render('Admin/Attributes/Attribute', [
            'types' => $types,
            'attribute' => $attribute,
        ]);
	}

	public function update(UpdateAttributeRequest $request, Attribute $attribute)
	{
        $attribute = $this->attributeRepository->updateAttribute($attribute->id, $request->only(['id','code','name','frontend_type','is_filterable','is_required']));

        return Redirect::route('admin.attributes.edit', $attribute)->with('success', 'Attribute has been successfully updated.');
	}

	public function destroy(Attribute $attribute)
	{
        $this->attributeRepository->deleteAttribute($attribute->id);
        return Redirect::route('admin.attributes.index')->with('success', 'Attribute has been successfully deleted.');
	}
}
