<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * Kita Model Filter
 *
 * @package \App\ModelFilters
 */
class KitaFilter extends BaseFilter
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
        return $this->where('name', 'LIKE', '%' . trim($value) . '%');
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
