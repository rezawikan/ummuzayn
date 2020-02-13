<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerAddress;
use App\Models\CustomerType;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_type_id', 'name', 'email', 'phone'
    ];

    /**
     * Get the customer type that owns the customer.
     */
    public function type()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

    /**
    * Get the customer addresses for the customer.
    */
    public function customer_addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
    * Get the default address for the customer.
    */
    public function default_address()
    {
        return $this->customer_addresses()->default();
    }

    /**
    * Scope a query to only include default value.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeDefault($query)
    {
        return $query->where('default', 1);
    }
}
