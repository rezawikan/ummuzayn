<?php

namespace App\Models;

use App\Models\City;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'city_id',
      'name'
    ];

    /**
    * Get the city that owns the subdistrict.
    */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
    * Get the customer addresses for the subdistrict.
    */
    public function customer_addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
