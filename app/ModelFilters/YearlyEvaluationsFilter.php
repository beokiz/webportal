<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * YearlyEvaluations Model Filter
 *
 * @package \App\ModelFilters
 */
class YearlyEvaluationsFilter extends BaseFilter
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
     * @param string $value
     * @return ModelFilter
     */
    public function year(string $value) : ModelFilter
    {
        return parent::stringFilter('year', $value);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withKitas($values) : ModelFilter
    {
        return $this->whereHas('kita', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withKitaNames($values) : ModelFilter
    {
        return $this->whereHas('kita', function ($query) use ($values) {
            $query->whereIn('name', (array) $values);
        });
    }

    /**
     * @param string|int $value
     * @return ModelFilter
     */
    public function children2BornPerYear($value) : ModelFilter
    {
        return $this->where('children_2_born_per_year', $value);
    }

    /**
     * @param string|int $value
     * @return ModelFilter
     */
    public function children4BornPerYear($value) : ModelFilter
    {
        return $this->where('children_4_born_per_year', $value);
    }

    /**
     * @param string|int $value
     * @return ModelFilter
     */
    public function evaluationsWithDaz2TotalPerYear($value) : ModelFilter
    {
        return $this->where('evaluations_with_daz_2_total_per_year', $value);
    }

    /**
     * @param string|int $value
     * @return ModelFilter
     */
    public function evaluationsWithDaz4TotalPerYear($value) : ModelFilter
    {
        return $this->where('evaluations_with_daz_4_total_per_year', $value);
    }
}
