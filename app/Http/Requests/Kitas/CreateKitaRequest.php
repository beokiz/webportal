<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Kitas;

use App\Http\Requests\BaseFormRequest;

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
        return [
            'name'                 => array_merge($this->textRules(), ['required']),
            'provider_of_the_kita' => array_merge($this->textRules(), ['required']),
            'city'                 => array_merge($this->textRules(), ['required']),
            'number'               => array_merge($this->bigIntegerRules(true), ['required']),
            'street'               => array_merge($this->textRules(), ['required']),
            'house_number'         => array_merge($this->textRules(true), ['required']),
            'additional_info'      => array_merge($this->textRules(8096), ['nullable']),
            'zip_code'             => array_merge($this->textRules(10), ['required']),
            'order'                => array_merge($this->integerRules(), ['nullable']),
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
