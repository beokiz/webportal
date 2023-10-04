<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Domains;

use App\Http\Requests\BaseFormRequest;

/**
 * Reorder Domain Request
 *
 * @package \App\Http\Requests\Domains
 */
class ReorderDomainsRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'items'         => ['required', 'array'],
            'items.*.id'    => ['required', $this->domainExistRule()],
            'items.*.order' => ['required', 'numeric'],
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
