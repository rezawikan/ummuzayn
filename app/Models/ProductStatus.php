<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeScoped;
use Laravel\Scout\Searchable;
use App\Models\Product;

class ProductStatus extends Model
{
    use CanBeScoped, Searchable;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'status', 'slug'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'product_status_index';
    }

    /**
     * Get the products for the product status.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'status_id');
    }
}
