<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IsParent
{

  /**
   * Scope a query to only include parents.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
    public function scopeParents(Builder $query)
    {
        $query->whereNull('parent_id');
    }
}
