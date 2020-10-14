<?php

namespace App\Scoping\Scopes\All;

use Illuminate\Database\Eloquent\Builder;
use App\Scoping\Contracts\Scope;

/**
 *
 */
class NameScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if(is_string($value)) {
            return $builder->whereRaw("UPPER(name) LIKE '%". strtoupper($value)."%'");
        }
        return $builder;
        
    }
}
