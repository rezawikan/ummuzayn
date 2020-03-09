<?php

namespace App\Scoping\Scopes\Address;

use Illuminate\Database\Eloquent\Builder;
use App\Scoping\Contracts\Scope;

/**
 *
 */
class CityScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        return $builder->where('city_id',$value);
    }
}
