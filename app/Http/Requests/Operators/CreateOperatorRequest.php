<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Operators;

use App\Http\Requests\BaseFormRequest;

/**
 * Create Operator Request
 *
 * @package \App\Http\Requests\Operators
 */
class CreateOperatorRequest extends BaseFormRequest
{
    /**
     * @return void
     */
    protected function prepareForValidation() : void
    {
        /*
         * Prepare boolean fields
         */
        $this->prepareBooleanFieldsForValidation([
            'self_training',
        ]);
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'          => array_merge($this->textRules(), ['required']),
            'self_training' => ['required', 'boolean'],
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
