<?php

namespace App\Models;

use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{

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
