<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Operators;

use App\Http\Requests\BaseFormRequest;

/**
 * Connect Users To Operator Request
 *
 * @package \App\Http\Requests\Operators
 */
class ConnectUsersToOperatorRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'users'   => ['required'],
            'users.*' => ['required', $this->userExistRule()],
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
