<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeOrderable;
use App\Models\Traits\CanBeScoped;
use App\Models\Subdistrict;
use App\Models\Customer;

class CustomerAddress extends Model
{
    use CanBeScoped, CanBeOrderable;

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

    /**
    * Scope a query to only include default value.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeCurrentAddress($query)
    {
        return $query->where('default', 1);
    }
}
