<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariation;
use Laravel\Scout\Searchable;

class ProductStock extends Model
{
    use Searchable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'product_variation_id', 'quantity', 'info'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'product_stocks_index';
    }

    /**
     * Get the product variation for the product stock.
     */
    public function product_variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
