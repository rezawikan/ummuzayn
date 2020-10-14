<?php

namespace App\Scoping\Scopes\All;

use Illuminate\Database\Eloquent\Builder;
use App\Scoping\Contracts\Scope;

/**
 *
 */
class SizeScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if(is_numeric($value) || is_double($value)) {
            return $builder->where('size', $value);
        }
        return $builder;
    }
}
