<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CanBeOrderable;
use App\Models\Traits\CanBeScoped;
use Laravel\Scout\Searchable;
use App\Models\Customer;

class CustomerType extends Model
{
    use CanBeScoped, CanBeOrderable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'slug'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'customer_types_index';
    }

    /**
     * Get the customers for the customer type.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
