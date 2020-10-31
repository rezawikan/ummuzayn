<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeScoped;
use App\Models\Product;

class ProductStatus extends Model
{
    use CanBeScoped;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'status', 'slug'
    ];

    /**
     * Get the products for the product status.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'status_id');
    }
}
