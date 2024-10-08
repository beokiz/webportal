<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Trainings;

use App\Http\Requests\BaseFormRequest;
use App\Models\Training;
use Illuminate\Validation\Rule;

/**
 * Create Training Request
 *
 * @package \App\Http\Requests\Trainings
 */
class CreateTrainingRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'multi_id'                       => ['required', $this->userExistRule()],
            'first_date'                     => ['required', 'date'],
            'first_date_start_and_end_time'  => ['required', 'date_format:H:i'],
            'second_date'                    => ['required', 'date'],
            'second_date_start_and_end_time' => ['required', 'date_format:H:i'],
            'location'                       => ['required', $this->textRules()],
            'max_participant_count'          => array_merge($this->bigIntegerRules(), ['required']),
            'type'                           => ['required', Rule::in(Training::TYPES)],
//            'status'                         => ['required', Rule::in(Training::STATUSES)],
            'street'                         => array_merge($this->textRules(), ['required']),
            'house_number'                   => array_merge($this->textRules(true), ['required']),
            'zip_code'                       => array_merge($this->textRules(10), ['required']),
            'city'                           => array_merge($this->textRules(), ['required']),
            'district'                       => array_merge($this->textRules(), ['required']),
            'notes'                          => ['nullable', $this->bigTextRules()],
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            //
        ];
    }
}
