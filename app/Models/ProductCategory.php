<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Traits\CanBeScoped;
use App\Models\Traits\IsParent;
use App\Models\Traits\CanBeOrderable;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use CanBeScoped, IsParent, CanBeOrderable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'slug'
    ];

    /**
     * Get the children for the product category.
     */
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id');
    }

    /**
     * Get the children recursive for the product category.
     */
    public function childrens()
    {
        return $this->children()->with('childrens');
    }

    /**
     * Get the products for the product category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
