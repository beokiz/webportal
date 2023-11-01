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
     * @return void
     */
    protected function prepareForValidation() : void
    {
        /*
         * Prepare boolean fields
         */
        $this->prepareBooleanFieldsForValidation([
            'is_daz',
        ]);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'uuid'                         => ['required', 'uuid'],
            'user_id'                      => ['required', $this->userExistRule()],
            'kita_id'                      => ['required', $this->kitaExistRule()],
            'age'                          => ['required', Rule::in(['2.5', '4.5'])],
            'is_daz'                       => ['required', 'boolean'],
            'ratings'                      => ['required', 'array'],
            'ratings.*.domain'             => ['required', $this->domainExistRule()],
            'ratings.*.milestones'         => ['required', 'array'],
            'ratings.*.milestones.*.id'    => ['required', $this->milestoneExistRule()],
            'ratings.*.milestones.*.value' => array_merge($this->tinyIntegerRules(), ['required']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            'ratings.*.domain'           => __('validation.attributes.domain'),
            'ratings.*.milestones'       => __('validation.attributes.milestones'),
            'ratings.*.milestones.id'    => __('validation.attributes.milestone') . ' ' . __('validation.attributes.id'),
            'ratings.*.milestones.value' => __('validation.attributes.milestone') . ' ' . __('validation.attributes.value'),
        ];
    }
}
