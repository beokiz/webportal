<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Carbon;

/**
 * Training Model Filter
 *
 * @package \App\ModelFilters
 */
class TrainingFilter extends BaseFilter
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
    public function withMultipliers($values) : ModelFilter
    {
        return $this->whereHas('multiplier', function ($query) use ($values) {
            $query->whereIn('id', (array) $values);
        });
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function firstDate(string $value) : mixed
    {
        if ($value) {
            return $this->whereDate('first_date', Carbon::parse($value)->format('Y-m-d'));
        }
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function secondDate(string $value) : mixed
    {
        if ($value) {
            return $this->whereDate('second_date', Carbon::parse($value)->format('Y-m-d'));
        }
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function afterFirstDate(string $value) : mixed
    {
        return $this->whereDate('first_date', '>', $value);
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function afterSecondDate(string $value) : mixed
    {
        return $this->whereDate('second_date', '>', $value);
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function location(string $value) : ModelFilter
    {
//        return parent::stringFilter('location', $value);
        return $this->whereRaw("CONCAT(location, ' - ', street, ' ', house_number, ' ', zip_code, ' ', city) LIKE ?",
            ["%{$value}%"]
        );
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function participantCount(string $value) : ModelFilter
    {
        return $this->where('participant_count', 'LIKE', trim($value) . '%');
    }

    /**
     * @param string $value
     * @return ModelFilter
     */
    public function maxParticipantCount(string $value) : ModelFilter
    {
        return $this->where('max_participant_count', 'LIKE', trim($value) . '%');
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
    public function status($values) : ModelFilter
    {
        return $this->whereIn('status', (array) $values);
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
     * @param string $values
     * @return ModelFilter
     */
    public function searchKita(string $value) : ModelFilter
    {
        return $this->whereHas('kitas', function ($query) use ($value) {
            $query->where('name', 'LIKE', '%' . trim($value) . '%');
        });
    }

    /**
     * @param mixed $value
     * @return ModelFilter
     */
    public function trainingProposal($value) : ModelFilter
    {
        return $this->where('training_proposal_id', $value);
    }
}
