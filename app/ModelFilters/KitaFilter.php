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
        return parent::stringFilter('name', $value);
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

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function approved($values) : ModelFilter
    {
        return $this->where(function ($query) use ($values) {
            foreach ((array) $values as $value) {
                $query->orWhere('approved', 'LIKE', filter_var($value, FILTER_VALIDATE_BOOLEAN));
            }
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function hasYearlyEvaluations($values) : ModelFilter
    {
        return $this->where(function ($query) use ($values) {
            if (in_array('true', $values)) {
                $query->orWhereHas('yearlyEvaluations');
            }

            if (in_array('false', $values)) {
                $query->orWhereDoesntHave('yearlyEvaluations');
            }
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function operator($values) : ModelFilter
    {
        return $this->whereIn('operator_id', (array) $values);
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function otherOperator(string $value) : ModelFilter
    {
        return $this->where('other_operator', 'LIKE', '%' . trim($value) . '%')
            ->whereNull('operator_id');
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function type($values) : ModelFilter
    {
        return $this->whereIn('type', (array) $values);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function zipCode($values) : ModelFilter
    {
        return $this->whereIn('zip_code', (array) $values);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withOperators($values) : ModelFilter
    {
        return $this->whereIn('operator_id', (array) $values);
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withTrainings($values) : ModelFilter
    {
        return $this->whereHas('trainings', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }

    /**
     * @param string|array $values
     * @return ModelFilter
     */
    public function withTrainingProposals($values) : ModelFilter
    {
        return $this->whereHas('trainingProposals', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }
}
