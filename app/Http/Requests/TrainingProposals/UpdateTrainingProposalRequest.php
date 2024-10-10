<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\TrainingProposals;

use App\Models\TrainingProposal;
use App\Rules\DateDifferenceRule;
use Illuminate\Validation\Rule;

/**
 * Update Training Proposal Request
 *
 * @package \App\Http\Requests\TrainingProposals
 */
class UpdateTrainingProposalRequest extends CreateTrainingProposalRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'multi_id'          => ['nullable', $this->userExistRule()],
            'first_date'        => ['sometimes', 'date'],
            'second_date'       => ['sometimes', 'date'], // OLD: 'different:first_date', new DateDifferenceRule('first_date', $this->input('first_date'), 7, 'less_than')
            'location'          => array_merge($this->textRules(), ['sometimes']),
            'participant_count' => array_merge($this->bigIntegerRules(), ['sometimes']),
            'status'            => ['sometimes', Rule::in(TrainingProposal::STATUSES)],
            'street'            => array_merge($this->textRules(), ['sometimes']),
            'house_number'      => array_merge($this->textRules(true), ['sometimes']),
            'zip_code'          => array_merge($this->textRules(10), ['sometimes']),
            'city'              => array_merge($this->textRules(), ['sometimes']),
            'district'          => array_merge($this->textRules(), ['nullable']),
            'notes'             => array_merge($this->bigTextRules(), ['nullable']),
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
