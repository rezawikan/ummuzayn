<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CanBeOrderable
{
    /**
     * Scope a query to include all requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  string|name  $column
     * @param  string|asc  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered(Builder $builder, $column = 'name', $direction = 'asc')
    {
        $builder->orderBy($column, $direction);
    }
}
