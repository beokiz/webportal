<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Domains;

use App\Http\Requests\BaseFormRequest;

/**
 * Create Domain Request
 *
 * @package \App\Http\Requests\Domains
 */
class CreateDomainRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'                       => array_merge($this->textRules(), ['required']),
            'abbreviation'               => array_merge($this->textRules(), ['required']),
            'order'                      => array_merge($this->integerRules(), ['nullable']),
            'daz_dependent'              => ['nullable', 'boolean'],
            'age_2_red_threshold'        => array_merge($this->smallIntegerRules(), ['required']),
            'age_2_red_threshold_daz'    => array_merge($this->smallIntegerRules(), ['required']),
            'age_2_yellow_threshold'     => array_merge($this->smallIntegerRules(), ['required']),
            'age_2_yellow_threshold_daz' => array_merge($this->smallIntegerRules(), ['required']),
            'age_4_red_threshold'        => array_merge($this->smallIntegerRules(), ['required']),
            'age_4_red_threshold_daz'    => array_merge($this->smallIntegerRules(), ['required']),
            'age_4_yellow_threshold'     => array_merge($this->smallIntegerRules(), ['required']),
            'age_4_yellow_threshold_daz' => array_merge($this->smallIntegerRules(), ['required']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            //
        ];
    }
}
