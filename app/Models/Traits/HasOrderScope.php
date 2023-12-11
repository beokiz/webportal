<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for adding "orderBy" scope to model
 *
 * @package \App\Models\Traits
 */
trait HasOrderScope
{
    /**
     * @param Builder $query
     * @param string  $column
     * @param bool    $desc
     * @return Builder
     */
    public function scopeCustomOrderBy(Builder $query, string $column = 'id', bool $desc = false) : Builder
    {
        return $desc
            ? $query->orderByDesc($column)
            : $query->orderBy($column);
    }
}
