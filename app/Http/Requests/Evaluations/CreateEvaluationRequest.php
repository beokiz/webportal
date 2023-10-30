<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Evaluations;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Create Evaluation Request
 *
 * @package \App\Http\Requests\Evaluations
 */
class CreateEvaluationRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'uuid'                      => ['required', 'uuid'],
            'user_id'                   => ['required', $this->userExistRule()],
            'kita_id'                   => ['required', $this->kitaExistRule()],
            'age'                       => ['required', Rule::in(['2.5', '4.5'])],
            'is_daz'                    => ['required', 'boolean'],
            'data'                      => ['required', 'array'],
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
        return [
            'data.*.milestone' => __('validation.attributes.milestone'),
            'data.*.value'     => __('validation.attributes.value'),
        ];
    }
}
