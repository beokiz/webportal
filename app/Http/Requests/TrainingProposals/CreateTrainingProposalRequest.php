<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\TrainingProposals;

use App\Http\Requests\BaseFormRequest;
use App\Models\TrainingProposal;
use App\Rules\DateDifferenceRule;
use Illuminate\Validation\Rule;

/**
 * Create Training Proposal Request
 *
 * @package \App\Http\Requests\TrainingProposals
 */
class CreateTrainingProposalRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
//            'multi_id'          => ['nullable', $this->userExistRule()],
            'first_date'        => ['required', 'date'],
            'second_date'       => ['required', 'date', 'different:first_date', new DateDifferenceRule('first_date', $this->input('first_date'), 7, 'less_than')],
            'location'          => ['required', $this->textRules()],
            'participant_count' => array_merge($this->bigIntegerRules(), ['required']),
//            'status'            => ['required', Rule::in(TrainingProposal::STATUSES)],
            'notes'             => ['nullable', $this->bigTextRules()],
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
