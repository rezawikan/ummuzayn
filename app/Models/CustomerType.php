<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class CustomerType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'slug'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
