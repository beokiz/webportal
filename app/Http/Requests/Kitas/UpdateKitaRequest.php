<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Kitas;

use App\Models\Kita;
use Illuminate\Validation\Rule;

/**
 * Update Kita Request
 *
 * @package \App\Http\Requests\Kitas
 */
class UpdateKitaRequest extends CreateKitaRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $user = $this->user();

        return [
            'operator_id'           => [$user->is_user_multiplier ? 'sometimes' : 'nullable', $this->operatorExistRule()],
            'other_operator'        => array_merge($this->textRules(), ['nullable']),
            'name'                  => array_merge($this->textRules(), ['sometimes']),
            'number'                => array_merge($this->bigIntegerRules(true), ['sometimes']),
            'street'                => array_merge($this->textRules(), ['sometimes']),
            'house_number'          => array_merge($this->textRules(true), ['sometimes']),
            'additional_info'       => array_merge($this->textRules(8096), ['nullable']),
            'zip_code'              => array_merge($this->textRules(10), ['sometimes']),
            'city'                  => array_merge($this->textRules(), ['sometimes']),
            'num_pedagogical_staff' => array_merge($this->bigIntegerRules(), ['nullable']),
            'approved'              => ['sometimes', 'boolean'],
            'type'                  => ['sometimes', Rule::in(Kita::TYPES)],
            'order'                 => array_merge($this->integerRules(), ['nullable']),
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
