<?php

namespace App\Scoping\Scopes\All;

use Illuminate\Database\Eloquent\Builder;
use App\Scoping\Contracts\Scope;

/**
 *
 */
class ParentScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if ($value === 'true') {
            return $builder->where('parent_id', null);
        }
        return $builder;
    }
}
