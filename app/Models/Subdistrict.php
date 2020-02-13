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
    * Get the customers for the subdistrict.
    */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
