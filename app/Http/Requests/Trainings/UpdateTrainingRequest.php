<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Trainings;

use App\Models\Training;
use Illuminate\Validation\Rule;

/**
 * Update Training Request
 *
 * @package \App\Http\Requests\Trainings
 */
class UpdateTrainingRequest extends CreateTrainingRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'multi_id'                       => ['sometimes', $this->userExistRule()],
            'first_date'                     => ['sometimes', 'date'],
            'first_date_start_and_end_time'  => ['sometimes', 'date_format:H:i'],
            'second_date'                    => ['sometimes', 'date'],
            'second_date_start_and_end_time' => ['sometimes', 'date_format:H:i'],
            'location'                       => array_merge($this->textRules(), ['sometimes']),
            'max_participant_count'          => array_merge($this->bigIntegerRules(), ['sometimes']),
//            'participant_count'              => array_merge($this->bigIntegerRules(), ['sometimes']),
            'type'                           => ['sometimes', Rule::in(Training::TYPES)],
            'status'                         => ['sometimes', Rule::in(Training::STATUSES)],
            'street'                         => array_merge($this->textRules(), ['sometimes']),
            'house_number'                   => array_merge($this->textRules(true), ['sometimes']),
            'zip_code'                       => array_merge($this->textRules(10), ['sometimes']),
            'city'                           => array_merge($this->textRules(), ['sometimes']),
            'notes'                          => array_merge($this->bigTextRules(), ['nullable']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return array_merge(parent::attributes(), [
            //
        ]);
    }
}
