<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * Base Model Filter
 *
 * @package \App\ModelFilters
 */
abstract class BaseFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]]
     * Or [relation => [input_key => relationMethod]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * Array of method names that should not be called.
     *
     * @var array
     */
    protected $blacklist = [];

    /**
     * @param string $column
     * @param string $value
     * @return ModelFilter
     */
    public function stringFilter(string $column, string $value) : ModelFilter
    {
        return $this->where($column, 'LIKE', '%' . trim($value) . '%');
    }

    /**
     * @param string $column
     * @param string $value
     * @return ModelFilter|null
     */
    protected function multipleStringFilter(string $column, string $value) : ?ModelFilter
    {
        return $this->where(function ($query) use ($column, $value) {
            foreach (explode(' ', $value) as $needle) {
                $this->orWhere($column, 'LIKE', '%' . trim($needle) . '%');
            }
        });
    }

    /**
     * @param string $column
     * @param mixed  $value
     * @return ModelFilter|null
     */
    protected function booleanFilter(string $column, $value) : ?ModelFilter
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($value === true) {
            return $this->where($column, '=', true);
        } else if ($value === false) {
            return $this->where($column, '=', false);
        } else {
            return null;
        }
    }
}
