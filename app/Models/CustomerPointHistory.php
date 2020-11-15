<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerPoint;
use App\Models\CustomerPointType;

class CustomerPointHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_point_id',
        'cp_history_type',
        'cp_history_id',
        'point',
        'description'
    ];

    /**
     * Get the customer for the customer point.
     */
    public function customer_point()
    {
        return $this->belongsTo(CustomerPoint::class);
    }

    /**
     * Get the model that the customer point history belongs to.
     */
    public function customer_point_historic()
    {
        return $this->morphTo(__FUNCTION__, 'cp_history_type', 'cp_history_id');
    }
}
