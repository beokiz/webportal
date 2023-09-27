<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Domains;

/**
 * Update User Request
 *
 * @package \App\Http\Requests\Domains
 */
class UpdateDomainRequest extends CreateDomainRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'                       => array_merge($this->textRules(), ['sometimes']),
            'slug'                       => array_merge($this->textRules(), ['sometimes']),
            'age_2_red_threshold'        => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_2_red_threshold_daz'    => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_2_yellow_threshold'     => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_2_yellow_threshold_daz' => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_4_red_threshold'        => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_4_red_threshold_daz'    => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_4_yellow_threshold'     => array_merge($this->smallIntegerRules(), ['sometimes']),
            'age_4_yellow_threshold_daz' => array_merge($this->smallIntegerRules(), ['sometimes']),
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
