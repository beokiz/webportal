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
     * @return array
     */
    public function rules() : array
    {
        return [
            'uuid'                      => ['sometimes', 'uuid'],
            'user_id'                   => ['sometimes', $this->userExistRule()],
            'kita_id'                   => ['sometimes', $this->kitaExistRule()],
            'age'                       => ['sometimes', Rule::in(['2.5', '4.5'])],
            'is_daz'                    => ['sometimes', 'boolean'],
            'data'                      => ['sometimes', 'array'],
            'data.*.domain'             => ['required', $this->domainExistRule()],
            'data.*.milestones'         => ['required', 'array'],
            'data.*.milestones.*.id'    => ['required', $this->milestoneExistRule()],
            'data.*.milestones.*.value' => array_merge($this->tinyIntegerRules(), ['required']),
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
