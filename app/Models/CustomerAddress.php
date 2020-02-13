<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subdistrict;
use App\Models\Customer;

class CustomerAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'customer_id',
      'subdistrict_id',
      'default',
      'name',
      'address',
      'phone'
    ];

    /**
    * Get the subdistrict that owns the customer addresses.
    */
    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    /**
    * Get the customer that owns the customer addresses.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
