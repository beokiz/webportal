<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Trainings;

use App\Http\Requests\BaseFormRequest;

/**
 * Add Kitas To Training Request
 *
 * @package \App\Http\Requests\Trainings
 */
class AddKitasToTrainingRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'kitas'   => ['required'],
            'kitas.*' => ['required', $this->kitaExistRule()],
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
