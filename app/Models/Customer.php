<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
     * Get the customer type record associated with the customer.
     */
    public function type()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }
}
