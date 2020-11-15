<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class ProductVariationOrder extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'order_id',
        'product_variation_id',
        'product_name',
        'product_description',
        'product_variation_type',
        'product_variation_name',
        'price',
        'base_price',
        'weight',
        'point',
        'quantity'
    ];

    /**
     * Get the status for the product.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
