<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;

/**
 * Update Password Request
 *
 * @package \App\Http\Requests\Auth
 */
class UpdatePasswordRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password'         => array_merge($this->passwordRules(), ['required']),
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
