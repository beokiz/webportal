<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Kitas;

use App\Http\Requests\BaseFormRequest;
use App\Models\Kita;
use Illuminate\Validation\Rule;

/**
 * Create Kitas Request
 *
 * @package \App\Http\Requests\Kitas
 */
class CreateKitaRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $user = $this->user();

        return [
            'operator_id'           => [$user->is_user_multiplier ? 'required' : 'nullable', $this->operatorExistRule()],
            'name'                  => array_merge($this->textRules(), ['required']),
            'number'                => array_merge($this->bigIntegerRules(true), ['required']),
            'street'                => array_merge($this->textRules(), ['required']),
            'house_number'          => array_merge($this->textRules(true), ['required']),
            'additional_info'       => array_merge($this->textRules(8096), ['nullable']),
            'zip_code'              => array_merge($this->textRules(10), ['required']),
            'city'                  => array_merge($this->textRules(), ['required']),
            'num_pedagogical_staff' => array_merge($this->bigIntegerRules(), ['nullable']),
            'approved'              => ['required', 'boolean'],
            'type'                  => ['required', Rule::in(Kita::TYPES)],
            'order'                 => array_merge($this->integerRules(), ['nullable']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            'number'          => __('validation.attributes.kita_number'),
            'additional_info' => __('validation.attributes.kita_additional_info'),
        ];
    }
}
