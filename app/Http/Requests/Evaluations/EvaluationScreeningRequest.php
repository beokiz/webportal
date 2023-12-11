<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Evaluations;

use Illuminate\Validation\Rule;

/**
 * Screening Evaluation Request
 *
 * @package \App\Http\Requests\Evaluations
 */
class EvaluationScreeningRequest extends CreateEvaluationRequest
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
            'age'                                 => ['required', Rule::in(['2.5', '4.5'])],
            'is_daz'                              => ['required', 'boolean'],
            'ratings'                             => ['required', 'array'],
            'ratings.*.domain'                    => ['required', $this->domainExistRule()],
            'ratings.*.milestones'                => ['required', 'array'],
            'ratings.*.milestones.*.id'           => ['required', $this->milestoneExistRule()],
            'ratings.*.milestones.*.abbreviation' => ['required', 'string'],
            'ratings.*.milestones.*.value'        => array_merge($this->tinyIntegerRules(), ['nullable']),
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
