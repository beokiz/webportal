<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\SurveyTimePeriods;

use Illuminate\Validation\Rule;

/**
 * Update Survey Time Period Request
 *
 * @package \App\Http\Requests\SurveyTimePeriods
 */
class UpdateSurveyTimePeriodRequest extends CreateSurveyTimePeriodRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'year'              => array_merge($this->bigIntegerRules(), ['sometimes', Rule::unique('survey_time_periods')->ignore($this->id)]),
            'survey_start_date' => ['sometimes', 'date'],
            'survey_end_date'   => ['sometimes', 'date', 'after:survey_start_date'],
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
