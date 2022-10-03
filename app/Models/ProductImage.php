<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as CastableAttribute;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = ['product_id', 'thumbnaul', 'full', 'main'];

    protected $casts = [
        'product_id' => 'integer',
        'main' => 'boolean',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function full(): CastableAttribute
    {
        return CastableAttribute::make(
            get: static function($value) {
                if(!is_null($value)) {
                    return asset('storage/' . $value);
                }
            }
        );
    }

    public function storageFull(): CastableAttribute
    {
        return CastableAttribute::make(
            get: static function($value, $attributes) {
                if(!is_null($attributes['full'])) {
                    return $attributes['full'];
                }

                return null;
            }
        );
    }
}
