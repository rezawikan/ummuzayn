<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeScoped;
use App\Models\ProductVariation;
use Laravel\Scout\Searchable;

class ProductVariationType extends Model
{
    use CanBeScoped, Searchable;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'variation_type', 'slug'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'product_variation_types_index';
    }

    /**
     * Get the product variations for the product variation type.
     */
    public function product_variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
