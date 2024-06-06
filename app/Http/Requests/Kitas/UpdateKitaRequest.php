<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Kitas;

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
        return [
            'name'                 => array_merge($this->textRules(), ['sometimes']),
            'provider_of_the_kita' => array_merge($this->textRules(), ['sometimes']),
            'city'                 => array_merge($this->textRules(), ['sometimes']),
            'number'               => array_merge($this->bigIntegerRules(true), ['sometimes']),
            'street'               => array_merge($this->textRules(), ['sometimes']),
            'house_number'         => array_merge($this->textRules(true), ['sometimes']),
            'additional_info'      => array_merge($this->textRules(8096), ['nullable']),
            'zip_code'             => array_merge($this->textRules(10), ['sometimes']),
            'order'                => array_merge($this->integerRules(), ['nullable']),
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
