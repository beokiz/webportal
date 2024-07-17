<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Operators;

use App\Http\Requests\BaseFormRequest;

/**
 * Cconnect Kita To Operator Request
 *
 * @package \App\Http\Requests\Operators
 */
class ConnectKitaToOperatorRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'kita' => ['required', $this->userExistRule()],
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
