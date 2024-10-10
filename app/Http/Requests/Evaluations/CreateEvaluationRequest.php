<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Evaluations;

use App\Http\Requests\BaseFormRequest;
use App\Models\Evaluation;
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
            'integration_status',
            'speech_therapy_status',
        ]);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'custom_unique_id'                    => ['required', 'string'],
            'uuid'                                => ['required', 'uuid'],
            'user_id'                             => ['required', $this->userExistRule()],
            'kita_id'                             => ['required', $this->kitaExistRule()],
            'age'                                 => array_merge($this->ageGroupRules(), ['required']),
            'child_duration_in_kita'              => array_merge($this->childDurationInKitaRules($this->input('age')), ['required']),
            'is_daz'                              => ['required', 'boolean'],
            'integration_status'                  => ['required', 'boolean'],
            'speech_therapy_status'               => ['required', 'boolean'],
            'ratings'                             => ['required', 'array'],
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
        return [
            'ratings.*.domain'           => __('validation.attributes.domain'),
            'ratings.*.milestones'       => __('validation.attributes.milestones'),
            'ratings.*.milestones.id'    => __('validation.attributes.milestone') . ' ' . __('validation.attributes.id'),
            'ratings.*.milestones.value' => __('validation.attributes.milestone') . ' ' . __('validation.attributes.value'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Additional methods
    |--------------------------------------------------------------------------
    */
    /**
     * @param string|null $age
     * @return array
     */
    protected function childDurationInKitaRules(?string $age) : array
    {
        $childDurationInKitaRules = [];

        if (!empty($age)) {
            $childDurationInKitaVars = $age === Evaluation::CHILD_AGE_GROUP_4
                ? Evaluation::CHILD_DURATION_IN_KITA_4_VARS
                : Evaluation::CHILD_DURATION_IN_KITA_2_VARS;

            $childDurationInKitaRules[] = Rule::in($childDurationInKitaVars);
        }

        return $childDurationInKitaRules;
    }
}
