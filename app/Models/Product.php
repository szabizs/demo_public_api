<?php

namespace App\Models;

use App\Traits\FilterableModel;
use App\Traits\UploadableFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UploadableFile;
    use FilterableModel;

    protected $table = 'products';

    protected $fillable = [
        'brand_id', 'sku', 'name', 'slug', 'description',
        'quantity', 'price', 'sale_price', 'active', 'featured'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'brand_id' => 'integer',
        'active' => 'boolean',
        'featured' => 'boolean',
    ];

    /**
     *
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(static function ($product) {
            $product->slug = Str::slug($product->name);
            $product->save();
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes() {
        return $this->hasMany(ProductAttribute::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id','category_id');
    }
}
