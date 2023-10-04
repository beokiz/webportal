<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Subdomains;

/**
 * Update Subdomain Request
 *
 * @package \App\Http\Requests\Subdomains
 */
class UpdateSubdomainRequest extends CreateSubdomainRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'  => array_merge($this->textRules(), ['sometimes']),
            'order' => array_merge($this->integerRules(), ['sometimes']),
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
