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
 * Create User From Kita Request
 *
 * @package \App\Http\Requests\Users
 */
class CreateUserFromKitaRequest extends CreateUserRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $rules = parent::rules();

        $rules['email'] = ['required', 'email'];

        if (!empty($this->input('kitas'))) {
            array_overwrite($rules, [
                'kitas'   => ['nullable'],
                'kitas.*' => ['required', $this->kitaExistRule()],
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
