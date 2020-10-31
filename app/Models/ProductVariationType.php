<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeScoped;
use App\Models\ProductVariation;

class ProductVariationType extends Model
{
    use CanBeScoped;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'variation_type', 'slug'
    ];

    /**
     * Get the product variations for the product variation type.
     */
    public function product_variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
