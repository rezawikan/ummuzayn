<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\CustomerPointHistory;
use Illuminate\Database\Eloquent\Model;

class CustomerReedemPoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'description',
        'point'
    ];

    /**
     * Get the customers for the customer type.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the order's point history.
     */
    public function customer_point_history()
    {
        return $this->morphOne(CustomerPointHistory::class, 'cp_history');
    }
}
