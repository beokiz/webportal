<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Evaluations;

use Illuminate\Validation\Rule;

/**
 * Update Evaluation Request
 *
 * @package \App\Http\Requests\Evaluations
 */
class UpdateEvaluationRequest extends CreateEvaluationRequest
{
    /**
     * @return void
     */
    protected function prepareForValidation() : void
    {
        parent::prepareForValidation();
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'uuid'                                => ['sometimes', 'uuid'],
            'user_id'                             => ['sometimes', $this->userExistRule()],
            'kita_id'                             => ['sometimes', $this->kitaExistRule()],
            'age'                                 => array_merge($this->ageGroupRules(), ['sometimes']),
            'child_duration_in_kita'              => ['sometimes', Rule::in(['upToOneYear', 'upToTwoYears', 'upToThreeYears'])],
            'integration_status'                  => ['sometimes', 'boolean'],
            'speech_therapy_status'               => ['sometimes', 'boolean'],
            'is_daz'                              => ['sometimes', 'boolean'],
            'ratings'                             => ['sometimes', 'array'],
            'ratings.*.domain'                    => ['required', $this->domainExistRule()],
            'ratings.*.milestones'                => ['required', 'array'],
            'ratings.*.milestones.*.id'           => ['required', $this->milestoneExistRule()],
            'ratings.*.milestones.*.abbreviation' => ['required', 'string'],
            'ratings.*.milestones.*.value'        => array_merge($this->tinyIntegerRules(true, true), ['required']),
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
