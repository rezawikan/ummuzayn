<?php

namespace App\Scoping\Scopes\Address;

use Illuminate\Database\Eloquent\Builder;
use App\Scoping\Contracts\Scope;

/**
 *
 */
class ProvinceScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        return $builder->where('province_id',$value);
    }
}
