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
            'name'     => array_merge($this->textRules(), ['sometimes']),
            'order'    => array_merge($this->integerRules(), ['nullable']),
            'zip_code' => array_merge($this->textRules(10), ['sometimes']),
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
