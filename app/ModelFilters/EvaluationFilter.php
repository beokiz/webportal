<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * Milestone Model Filter
 *
 * @package \App\ModelFilters
 */
class EvaluationFilter extends BaseFilter
{
    /**
     * @var array
     */
    public $relations = [];

    /**
     * @var array
     */
    protected $blacklist = [];

    /**
     * @return void
     */
    public function setup() : void
    {
        //
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withUsers($values) : ModelFilter
    {
        return $this->whereHas('user', function ($query) use ($values) {
            return $query->whereIn('id', (array) $values);
        });
    }

    /**
     * @param array $period
     * @return ModelFilter|void
     */
    public function finishedBetween(array $period)
    {
        if (!empty($period['from']) && !empty($period['to'])) {
            return $this->whereBetween('finished_at', [$period['from'], $period['to']]);
        }
    }

    /**
     * @param array $period
     * @return ModelFilter|void
     */
    public function finishedBetweenOrNull(array $period)
    {
        if (!empty($period['from']) && !empty($period['to'])) {
            return $this->where(function ($query) use ($period) {
                return $query->whereBetween('finished_at', [$period['from'], $period['to']])
                    ->orWhere('finished_at', null);
            });
        }
    }
}
