<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\TrainingProposals;

use App\Http\Requests\BaseFormRequest;

/**
 * Add Multiplier To Training Proposal Request
 *
 * @package \App\Http\Requests\TrainingProposals
 */
class AddMultiplierToTrainingProposalRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'multi_id' => ['required', $this->userExistRule()],
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
