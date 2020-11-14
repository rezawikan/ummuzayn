<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Oder;

class OrderStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'slug'
    ];

    /**
    * Get the orders for the status.
    */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
