<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderStatus;
use App\Models\ProductVariationOrder;
use App\Models\CustomerPointHistory;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'order_status_id',
        'subtotal',
        'base_subtotal',
        'marketplace_fee',
        'discount',
        'total',
        'total_profit',
        'point'
    ];

    /**
    * Get the status for the order.
    */
    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    /**
    * Get the customer for the order.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
    * Get the product variation order for the order.
    */
    public function product_variation_orders()
    {
        return $this->hasMany(ProductVariationOrder::class);
    }

    /**
     * Get the order's point history.
     */
    public function customer_point_history()
    {
        return $this->morphOne(CustomerPointHistory::class, 'cp_history');
    }
}
