<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

/**
 * User Model Filter
 *
 * @package \App\ModelFilters
 */
class UserFilter extends BaseFilter
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
    public function firstName(string $value) : ModelFilter
    {
        return parent::stringFilter('first_name', $value);
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function lastName(string $value) : ModelFilter
    {
        return parent::stringFilter('last_name', $value);
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function fullName(string $value) : ModelFilter
    {
        return $this->where(function ($query) use ($value) {
            if (config('database.default') === 'mysql') {
                return $query->whereRaw("CONCAT(COALESCE(`first_name`,''), ' ', COALESCE(`last_name`,'')) LIKE ?", ["%$value%"])
                    ->orWhereRaw("CONCAT(COALESCE(`last_name`,''), ' ', COALESCE(`first_name`,'')) LIKE ?", ["%$value%"]);
            } else if (config('database.default') === 'pgsql') {
                return $query->whereRaw("CONCAT(COALESCE(\"first_name\",''), ' ', COALESCE(\"last_name\",'')) ILIKE ?", ["%$value%"])
                    ->orWhereRaw("CONCAT(COALESCE(\"last_name\",''), ' ', COALESCE(\"first_name\",'')) ILIKE ?", ["%$value%"]);
            }
        });
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function email(string $value) : ModelFilter
    {
        return parent::stringFilter('email', $value);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withRoles($values) : ModelFilter
    {
        return $this->whereHas('roles', function ($query) use ($values) {
            $query->whereIn('name', (array) $values);
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withoutRoles($values) : ModelFilter
    {
        return $this->whereHas('roles', function ($query) use ($values) {
            $query->whereNotIn('name', (array) $values);
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withKitas($values) : ModelFilter
    {
        return $this->whereHas('kitas', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withOperators($values) : ModelFilter
    {
        return $this->whereHas('operators', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }
}
