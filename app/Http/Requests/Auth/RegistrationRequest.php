<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Models\Kita;
use App\Models\User;
use Illuminate\Validation\Rule;

/**
 * Registration Request
 *
 * @package \App\Http\Requests\Auth
 */
class RegistrationRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $rules = [
            // Kita fields
            'kita'                   => ['required', 'array'],
            'kita.number'            => array_merge($this->bigIntegerRules(true), ['required']),
            'kita.name'              => array_merge($this->textRules(), ['required']),
            'kita.district'          => array_merge($this->textRules(), ['nullable']),
            'kita.street'            => array_merge($this->textRules(), ['required']),
            'kita.house_number'      => array_merge($this->textRules(true), ['required']),
            'kita.zip_code'          => array_merge($this->textRules(10), ['required']),
            'kita.city'              => array_merge($this->textRules(), ['required']),
            'kita.additional_info'   => array_merge($this->textRules(8096), ['nullable']),
            'kita.participant_count' => array_merge($this->bigIntegerRules(), ['nullable']),
            'kita.type'              => ['required', Rule::in(Kita::TYPES)],
            // User fields
            'user'                   => ['required', 'array'],
            'user.first_name'        => array_merge($this->textRules(), ['required']),
            'user.last_name'         => array_merge($this->textRules(), ['required']),
            'user.email'             => ['required', 'email', Rule::unique(User::class, 'email')],
            'user.phone_number'      => ['nullable', 'string'],
        ];

        // Additional validation based on operator & kita type
        $kitaType      = $this->input('kita.type');
        $operatorId    = $this->input('kita.operator_id');
        $otherOperator = $this->input('kita.other_operator');

        if (!empty($operatorId) && empty($otherOperator)) {
            $rules['kita.operator_id'] = ['required', $this->operatorExistRule()];
        } else {
            $rules['kita.other_operator'] = array_merge($this->textRules(), ['required']);

            if ($kitaType === Kita::TYPE_LARGE) {
                $rules['kita.trainings.*.first_date']  = ['required', 'date'];
                $rules['kita.trainings.*.second_date'] = ['required', 'date']; // OLD: 'different:kita.trainings.*.first_date', new DateDifferenceRule('first_date', $this->input('first_date'), 7, 'less_than')
            } else {
                $rules['kita.training_id'] = ['required', $this->trainingExistRule()];
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            'kita.number'                  => __('validation.attributes.kita_number'),
            'kita.name'                    => __('validation.attributes.name'),
            'kita.district'                => __('validation.attributes.district'),
            'kita.street'                  => __('validation.attributes.street'),
            'kita.house_number'            => __('validation.attributes.house_number'),
            'kita.zip_code'                => __('validation.attributes.zip_code'),
            'kita.city'                    => __('validation.attributes.city'),
            'kita.additional_info'         => __('validation.attributes.additional_info'),
            'kita.participant_count'       => __('validation.attributes.participant_count'),
            'kita.type'                    => __('validation.attributes.type'),
            'kita.operator_id'             => __('validation.attributes.operator_id'),
            'kita.other_operator'          => __('validation.attributes.other_operator'),
            'kita.training_id'             => __('validation.attributes.training_id'),
            'kita.trainings.*.first_date'  => __('validation.attributes.first_date'),
            'kita.trainings.*.second_date' => __('validation.attributes.second_date'),
            'user.first_name'              => __('validation.attributes.first_name'),
            'user.last_name'               => __('validation.attributes.last_name'),
            'user.email'                   => __('validation.attributes.email'),
            'user.phone_number'            => __('validation.attributes.phone_number'),
        ];
    }
}
