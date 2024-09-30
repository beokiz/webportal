<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Validation\Rule;

//use Illuminate\Validation\Rules;

/**
 * Update User Request
 *
 * @package \App\Http\Requests\Users
 */
class UpdateUserRequest extends CreateUserRequest
{
    /**
     * @return void
     */
    protected function prepareForValidation() : void
    {
        parent::prepareForValidation();

        // If the ID of the edited user is equal to the ID of the current one, then we remove the “role” field to prevent
        // possible logical errors (for example, so that Super admin cannot remove this role from himself,
        // because if there are no other users with this role, he will no longer be able to return)
        if ($this->user()->id === $this->route('user')->id) {
            request()->request->remove('role');
        }
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        $rules = [
            'first_name'              => array_merge($this->textRules(), ['sometimes']),
            'last_name'               => array_merge($this->textRules(), ['nullable']),
            'email'                   => ['sometimes', 'email', Rule::unique(User::class)->ignore($this->route('user'))],
            'role'                    => ['sometimes', $this->roleExistRule(config('permission.project_roles'))],
            'two_factor_auth_enabled' => ['sometimes', 'boolean'],
            'phone_number'            => ['nullable', 'string'],
        ];

        if (!empty($this->input('password'))) {
            array_overwrite($rules, [
                'password' => array_merge($this->passwordRules(), ['required']),
            ]);
        }

        return $rules;
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
