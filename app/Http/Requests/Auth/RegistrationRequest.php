<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * Registration Request
 *
 * @package \App\Http\Requests\Auth
 */
class RegistrationRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name' => array_merge($this->textRules(), ['required']),
            'last_name'  => array_merge($this->textRules(), ['nullable']),
            'email'      => ['required', 'email', Rule::unique(User::class)],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
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
