<?php

namespace App\Models;

use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\Traits\CanBeScoped;
use App\Models\Traits\CanBeOrderable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use CanBeScoped, CanBeOrderable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'capital',
      'province_id'
    ];

    /**
    * check the city has subdistricts.
    */
    public function has_subdistricts()
    {
        return $this->subdistricts->count() > 0;
    }

    /**
    * Get the subdistricts for the city.
    */
    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }

    /**
    * Get the province that owns the city.
    */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
