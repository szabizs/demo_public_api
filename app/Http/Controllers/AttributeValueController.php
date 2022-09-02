<?php

namespace App\Http\Controllers;

use App\Contracts\AttributeContract;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AttributeValueController extends Controller
{

    private AttributeContract $attributeRepository;

    public function __construct(AttributeContract $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    public function getValues()
    {
        $attributeId = request()->get('id');
        $attribute = $this->attributeRepository->findAttributeById($attributeId);

        $values = $attribute->values;

        return response()->json($values);
    }

    public function addValues(Request $request)
    {
        $value = new AttributeValue();
        $value->attribute_id = $request->input('id');
        $value->value = $request->input('value');
        $value->save();

        return response()->json([
            'value' => $value
        ]);
    }

    public function updateValues(Request $request)
    {
        $value = AttributeValue::findOrFail($request->input('valueId'));
        $value->attribute_id = $request->input('id');
        $value->value = $request->input('value');
        $value->save();

        return response()->json([
            'value' => $value
        ]);
    }

    public function destroyValues($id)
    {
        AttributeValue::find($id)->delete();
        return response()->json([
            'message' => 'Value has been deleted.'
        ]);
    }
}
