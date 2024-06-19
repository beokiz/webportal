<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Milestones;

use App\Http\Requests\BaseFormRequest;

/**
 * Create Subdomain Request
 *
 * @package \App\Http\Requests\Milestones
 */
class CreateMilestoneRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'subdomain'    => ['required', $this->subdomainExistRule()],
            'abbreviation' => array_merge($this->textRules(), ['required']),
            'title'        => array_merge($this->textRules(), ['required']),
            'text'         => array_merge($this->bigTextRules(), ['required']),
            'order'        => array_merge($this->integerRules(), ['sometimes']),
            'emphasis'     => array_merge($this->floatRules(), ['required']),
            'emphasis_daz' => array_merge($this->floatRules(), ['required']),
            'age'          => array_merge($this->ageGroupRules(), ['required']),
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
