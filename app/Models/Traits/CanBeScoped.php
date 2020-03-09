<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Scoping\Scoper;

trait CanBeScoped
{
    /**
     * Scope a query to include all requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|[] $scopes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithScopes(Builder $builder, $scopes = [])
    {
        return (new Scoper(request()))->apply($builder, $scopes);
    }
}
