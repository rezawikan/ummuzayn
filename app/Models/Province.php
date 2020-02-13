<?php

namespace App\Models;

use App\Models\City;
use App\Models\Subdistrict;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
    /**
    * Get all of the subdistricts for the province.
    */
    public function subdistricts()
    {
        return $this->hasManyThrough(Subdistrict::class, City::class);
    }

    /**
    * Get the cities for the province.
    */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
