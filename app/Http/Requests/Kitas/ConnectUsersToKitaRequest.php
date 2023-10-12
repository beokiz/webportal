<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Kitas;

use App\Http\Requests\BaseFormRequest;

/**
 * Connect Users To Kita Request
 *
 * @package \App\Http\Requests\Kitas
 */
class ConnectUsersToKitaRequest extends BaseFormRequest
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
