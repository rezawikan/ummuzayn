<?php

namespace App\Models;

use App\Models\Province;
use App\Models\Subdistrict;
use Laravel\Scout\Searchable;
use App\Models\Traits\CanBeScoped;
use App\Models\Traits\CanBeOrderable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use CanBeScoped, CanBeOrderable, Searchable;

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
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'cities_index';
    }

    
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
