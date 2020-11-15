<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerPointHistory;
use App\Models\Customer;

class CustomerPoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_points'
    ];

    /**
     * Get the customer for the customer point.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the customer point histories for the customer point.
     */
    public function customer_point_histories()
    {
        return $this->hasMany(CustomerPointHistory::class);
    }
}
