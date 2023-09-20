<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * Role Model Filter
 *
 * @package \App\ModelFilters
 */
class RoleFilter extends BaseFilter
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
    public function setup()
    {
        //
    }

    /**
     * @param array|int $values
     * @return ModelFilter
     */
    public function exclude($values) : ModelFilter
    {
        return $this->whereNotIn('id', (array) $values);
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function name(string $value) : ModelFilter
    {
        return parent::stringFilter('name', $value);
    }
}
