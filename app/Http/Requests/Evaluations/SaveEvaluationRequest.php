<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Evaluations;

/**
 * Create Evaluation Request
 *
 * @package \App\Http\Requests\Evaluations
 */
class SaveEvaluationRequest extends CreateEvaluationRequest
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
        $rules = [
            'custom_unique_id'       => ['required', 'string'],
            'id'                     => ['nullable', 'numeric'],
            'uuid'                   => ['required', 'uuid'],
            'user_id'                => ['required', $this->userExistRule()],
            'kita_id'                => ['nullable', $this->kitaExistRule()],
            'age'                    => array_merge($this->ageGroupRules(), ['nullable']),
            'child_duration_in_kita' => array_merge($this->childDurationInKitaRules($this->input('age')), ['nullable']),
            'is_daz'                 => ['nullable', 'boolean'],
            'integration_status'     => ['nullable', 'boolean'],
            'speech_therapy_status'  => ['nullable', 'boolean'],
            'ratings'                => ['nullable', 'array'],
        ];

        if ($this->input('ratings')) {
            array_overwrite($rules, [
                'ratings.*.domain'                    => ['nullable', $this->domainExistRule()],
                'ratings.*.milestones'                => ['nullable', 'array'],
                'ratings.*.milestones.*.id'           => ['nullable', $this->milestoneExistRule()],
                'ratings.*.milestones.*.abbreviation' => ['nullable', 'string'],
                'ratings.*.milestones.*.value'        => array_merge($this->tinyIntegerRules(true, true), ['nullable']),
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
