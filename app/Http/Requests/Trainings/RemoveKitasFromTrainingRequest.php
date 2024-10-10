<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Trainings;

/**
 * Remove Kitas From Training Request
 *
 * @package \App\Http\Requests\Trainings
 */
class RemoveKitasFromTrainingRequest extends AddKitasToTrainingRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return array_merge(parent::rules(), [
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
