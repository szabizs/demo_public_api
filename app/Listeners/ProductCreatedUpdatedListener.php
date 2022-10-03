<?php

namespace App\Listeners;

use App\Events\Product\ProductCreatedAfterEvent;
use App\Models\AttributeOption;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductFlat;
use Illuminate\Support\Facades\Schema;

class ProductCreatedUpdatedListener
{
    public function __construct()
    {
        //
    }

    public function handle(ProductCreatedAfterEvent $event)
    {
        $this->createFlat($event->getProduct());
    }

    /**
     * Creates product flat
     *
     * @param  Product  $product
     * @param  Product  $parentProduct
     *
     * @return void
     */
    public function createFlat(Product $product, Product $parentProduct = null)
    {
        static $familyAttributes = [];

        static $superAttributes = [];

        if (!array_key_exists($product->attribute_family->id, $familyAttributes)) {
            $familyAttributes[$product->attribute_family->id] = $product->attribute_family->custom_attributes;
        }

        if ($parentProduct && !array_key_exists($parentProduct->id, $superAttributes)) {
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();
        }
        $productFlat = ProductFlat::where('product_id', $product->id)->first();

        if (is_null($productFlat)) {
            $productFlat = ProductFlat::create([
                'product_id' => $product->id,
                'sku' => $product->sku,
            ]);
        }

        foreach ($familyAttributes[$product->attribute_family->id] as $attribute) {
            if ($parentProduct && !in_array($attribute->code,
                    array_merge($superAttributes[$parentProduct->id], $this->fillableAttributeCodes))) {
                continue;
            }

            if (in_array($attribute->code, ['tax_category_id'])) {
                continue;
            }

            if (!Schema::hasColumn('product_flat', $attribute->code)) {
                continue;
            }

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    $productAttributeValue = $product->attribute_values()
                        ->where('attribute_id', $attribute->id)
                        ->first();
                } else {
                    $productAttributeValue = $product->attribute_values()
                        ->where('attribute_id', $attribute->id)
                        ->first();
                }
            } else {
                if ($attribute->value_per_locale) {
                    $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                } else {
                    $productAttributeValue = $product->attribute_values()->where('attribute_id',
                        $attribute->id)->first();
                }
            }

            $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;

            if ($attribute->type == 'select') {
                $attributeOption = AttributeOption::find($product->{$attribute->code});

                if ($attributeOption) {
                    $productFlat->{$attribute->code.'_label'} = $attributeOption->admin_name;
                }
            } elseif ($attribute->type == 'multiselect') {
                $attributeOptionIds = explode(',', $product->{$attribute->code});

                if (count($attributeOptionIds)) {
                    $attributeOptions = AttributeOption::find($attributeOptionIds);

                    $optionLabels = [];

                    foreach ($attributeOptions as $attributeOption) {
                        $optionLabels[] = $attributeOption->admin_name;
                    }

                    $productFlat->{$attribute->code.'_label'} = implode(', ', $optionLabels);
                }
            }
        }

        $productFlat->created_at = $product->created_at;
        $productFlat->sku = $product->sku;
        $productFlat->updated_at = $product->updated_at;

//        $productFlat->min_price = $product->getTypeInstance()->getMinimalPrice();

//        $productFlat->max_price = $product->getTypeInstance()->getMaximamPrice();

        if ($parentProduct) {
            $parentProductFlat = ProductFlat::where('product_id',$parentProduct->id)->first();

            if ($parentProductFlat) {
                $productFlat->parent_id = $parentProductFlat->id;
            }
        }

        $productFlat->save();
    }
}
