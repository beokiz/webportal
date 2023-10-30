<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * Domain Model Filter
 *
 * @package \App\ModelFilters
 */
class DomainFilter extends BaseFilter
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
    public function search(string $value) : ModelFilter
    {
        return $this->where('name', 'LIKE', '%' . trim($value) . '%')
            ->orWhere('abbreviation', 'LIKE', '%' . trim($value) . '%');
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function only($values) : ModelFilter
    {
        return $this->whereIn('id', (array) $values);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function exclude($values) : ModelFilter
    {
        return $this->whereNotIn('id', (array) $values);
    }
}
