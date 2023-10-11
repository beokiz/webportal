<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Kitas;

use App\Http\Requests\BaseFormRequest;

/**
 * Reorder Kitas Request
 *
 * @package \App\Http\Requests\Kitas
 */
class ReorderKitasRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'items'         => ['required', 'array'],
            'items.*.id'    => ['required', $this->kitaExistRule()],
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
