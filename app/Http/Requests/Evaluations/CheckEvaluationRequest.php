<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Evaluations;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * Check Evaluation Request
 *
 * @package \App\Http\Requests\Evaluations
 */
class CheckEvaluationRequest extends CreateEvaluationRequest
{
    /**
     * @return void
     */
    protected function prepareForValidation() : void
    {
        parent::prepareForValidation();
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return array_merge(Arr::except(parent::rules(), ['uuid', 'user_id', 'kita_id']), [
            //
        ]);
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
