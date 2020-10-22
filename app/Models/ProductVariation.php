<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariationType;
use App\Models\Traits\CanBeScoped;
use Laravel\Scout\Searchable;
use App\Models\ProductStock;
use App\Models\Product;

class ProductVariation extends Model
{
    use CanBeScoped, Searchable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'product_id', 'product_variation_type_id', 'variation_name', 'price', 'base_price', 'weight', 'orderable', 'stock'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'product_variations_index';
    }

    /**
   * Get the user's full name.
   *
   * @return string
   */
    public function getHasTypeAttribute()
    {
        return $this->product_variation_type()->exists() ? 1 : 0;
    }

    /**
     * Get the product for the product variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variation type for the product variation.
     */
    public function product_variation_type()
    {
        return $this->belongsTo(ProductVariationType::class);
    }
}
