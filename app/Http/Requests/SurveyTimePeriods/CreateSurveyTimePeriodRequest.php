<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\SurveyTimePeriods;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Create Survey Time Period Request
 *
 * @package \App\Http\Requests\SurveyTimePeriods
 */
class CreateSurveyTimePeriodRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'year'              => array_merge($this->bigIntegerRules(), ['required', Rule::unique('survey_time_periods')]),
            'survey_start_date' => ['required', 'date'],
            'survey_end_date'   => ['required', 'date', 'after:survey_start_date'],
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
