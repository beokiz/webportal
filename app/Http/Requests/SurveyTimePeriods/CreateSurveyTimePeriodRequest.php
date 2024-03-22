<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\SurveyTimePeriods;

use App\Http\Requests\BaseFormRequest;

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
            'year'              => array_merge($this->yearRules(), ['required']),
            'age'               => array_merge($this->ageGroupRules(), ['required']),
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
            'age' => __('validation.attributes.age_year'),
        ];
    }
}
