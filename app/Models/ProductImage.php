<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeScoped;
use App\Models\Product;

class ProductImage extends Model
{
    use CanBeScoped;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'product_id', 'size', 'location', 'format'
    ];

    /**
     * Get the product for the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
