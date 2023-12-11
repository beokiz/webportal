<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

//use Illuminate\Validation\Rules;

/**
 * Create User Request
 *
 * @package \App\Http\Requests\Users
 */
class CreateUserRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name'              => array_merge($this->textRules(), ['required']),
            'last_name'               => array_merge($this->textRules(), ['nullable']),
            'email'                   => ['required', 'email', Rule::unique(User::class)],
//            'password'                => array_merge($this->passwordRules(), ['required']),
            'role'                    => ['required', $this->roleExistRule(config('permission.project_roles'))],
            'two_factor_auth_enabled' => ['required', 'boolean'],
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
