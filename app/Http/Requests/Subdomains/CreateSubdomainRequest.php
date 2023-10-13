<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Subdomains;

use App\Http\Requests\BaseFormRequest;

/**
 * Create Subdomain Request
 *
 * @package \App\Http\Requests\Subdomains
 */
class CreateSubdomainRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'domain' => ['required', $this->domainExistRule()],
            'name'   => array_merge($this->textRules(), ['required']),
            'order'  => array_merge($this->integerRules(), ['sometimes']),
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
