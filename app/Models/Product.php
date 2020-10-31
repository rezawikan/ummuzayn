<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeOrderable;
use App\Models\Traits\CanBeScoped;
use App\Models\ProductVariation;
use App\Models\ProductStatus;
use App\Models\ProductImage;

class Product extends Model
{
    use CanBeScoped, CanBeOrderable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'product_category_id', 'name', 'description', 'status_id'
    ];

    /**
     * Get the status for the product.
     */
    public function status()
    {
        return $this->belongsTo(ProductStatus::class);
    }

    /**
     * Get the product variations for the product.
     */
    public function product_variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Check the variation type for the product.
     */
    public function has_variation_type()
    {
        return $this->product_variations->sum('has_type') > 0;
    }

    /**
     * Get the product images for the product.
     */
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
