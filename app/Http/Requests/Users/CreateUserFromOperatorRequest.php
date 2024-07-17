<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Users;

//use Illuminate\Validation\Rules;

/**
 * Create User From Operator Request
 *
 * @package \App\Http\Requests\Users
 */
class CreateUserFromOperatorRequest extends CreateUserRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $rules = parent::rules();

        $rules['email'] = ['required', 'email'];

        if (!empty($this->input('operators'))) {
            array_overwrite($rules, [
                'operators'   => ['nullable'],
                'operators.*' => ['required', $this->operatorExistRule()],
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
