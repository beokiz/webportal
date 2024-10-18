<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseFormRequest;

/**
 * Connect Operator To User Request
 *
 * @package \App\Http\Requests\Users
 */
class ConnectOperatorToUserRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'operator' => ['required', $this->operatorExistRule()],
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
