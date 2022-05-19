<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public $fillable = ['name','parent_id'];
    protected $table = 'categories';

    /**
     * Return the children of the model, if exists.
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->select(['id','parent_id','name','slug']);
    }

    /**
     * Return the parents of the model, if exists.
     * @return HasMany
     */
    public function parent(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Returns the categories of each category, recursively
     * @return HasMany
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with(['childrenRecursive']);
    }
}
