<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeScoped;
use Laravel\Scout\Searchable;
use App\Models\Product;

class ProductImage extends Model
{
    use CanBeScoped, Searchable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'product_id', 'size', 'location', 'format'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'product_images_index';
    }

    /**
     * Get the product for the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
