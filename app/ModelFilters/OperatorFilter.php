<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * Operator Model Filter
 *
 * @package \App\ModelFilters
 */
class OperatorFilter extends BaseFilter
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
     * @param mixed $value
     * @return ModelFilter
     */
    public function selfTraining($value) : ModelFilter
    {
        return parent::booleanFilter($value);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withUsers($values) : ModelFilter
    {
        return $this->whereHas('users', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }
}
