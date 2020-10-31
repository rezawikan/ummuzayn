<?php

namespace App\Models;

use App\Models\City;
use App\Models\Subdistrict;
use App\Models\Traits\CanBeScoped;
use App\Models\Traits\CanBeOrderable;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use CanBeScoped, CanBeOrderable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
    * check the province has cities.
    */
    public function has_cities()
    {
        return $this->cities->count() > 0;
    }

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
