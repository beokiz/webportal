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
class MilestoneFilter extends BaseFilter
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
        return $this->where('title', 'LIKE', '%' . trim($value) . '%')
            ->orWhere('abbreviation', 'LIKE', '%' . trim($value) . '%')
            ->orWhere('text', 'LIKE', '%' . trim($value) . '%');
    }
}
