<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for adding "orderBy" by joined table scope to model
 *
 * @package \App\Models\Traits
 */
trait HasOrderByJoinTableScope
{
    /**
     * @param Builder $query
     * @param string  $joinedTable
     * @param string  $column
     * @param bool    $desc
     * @return Builder
     */
    public function scopeOrderByJoinedTable(Builder $query, string $joinedTable, string $column = 'id', bool $desc = false) : Builder
    {
        $clause = "{$joinedTable}.{$column}";

        return $desc
            ? $query->orderByDesc($clause)
            : $query->orderBy($clause);
    }
}
