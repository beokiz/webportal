<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Milestones;

use Illuminate\Validation\Rule;

/**
 * Update Subdomain Request
 *
 * @package \App\Http\Requests\Milestones
 */
class UpdateMilestoneRequest extends CreateMilestoneRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'subdomain'    => ['sometimes', $this->subdomainExistRule()],
            'abbreviation' => array_merge($this->textRules(), ['sometimes']),
            'title'        => array_merge($this->textRules(), ['sometimes']),
            'text'         => array_merge($this->bigTextRules(), ['sometimes']),
            'order'        => array_merge($this->integerRules(), ['sometimes']),
            'emphasis'     => array_merge($this->floatRules(), ['sometimes']),
            'emphasis_daz' => array_merge($this->floatRules(), ['sometimes']),
            'age'          => ['sometimes', Rule::in(['2.5', '4.5'])],
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
