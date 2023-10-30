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
class SaveEvaluationRequest extends CreateEvaluationRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $rules = [
            'id'      => ['nullable', 'numeric'],
            'uuid'    => ['required', 'uuid'],
            'user_id' => ['required', $this->userExistRule()],
            'kita_id' => ['nullable', $this->kitaExistRule()],
            'age'     => ['nullable', Rule::in(['2.5', '4.5'])],
            'is_daz'  => ['nullable', 'boolean'],
            'data'    => ['nullable', 'array'],
        ];

        if ($this->input('data')) {
            array_overwrite($rules, [
                'data.*.domain'             => ['nullable', $this->domainExistRule()],
                'data.*.milestones'         => ['nullable', 'array'],
                'data.*.milestones.*.id'    => ['nullable', $this->milestoneExistRule()],
                'data.*.milestones.*.value' => array_merge($this->tinyIntegerRules(true , true), ['nullable']),
            ]);
        }

        return $rules;
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
