<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Observers;

use App\Models\Kita;
use App\Models\TrainingProposal;
use App\Models\User;

/**
 * Observer for TrainingProposal Model
 *
 * @package \App\Observers
 */
class TrainingProposalObserver extends BaseObserver
{
    /**
     * TrainingProposalObserver constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param TrainingProposal $trainingProposal
     * @return void
     */
    public function created(TrainingProposal $trainingProposal)
    {
        //
    }

    /**
     * @param TrainingProposal $trainingProposal
     * @return void
     */
    public function updated(TrainingProposal $trainingProposal)
    {
        if ($trainingProposal->isDirty('status')) {
            $trainingProposal->loadMissing(['multiplier']);

            switch ($trainingProposal->status) {
                case TrainingProposal::STATUS_OPEN:
                    //
                    break;
                case TrainingProposal::STATUS_OBSOLETE:
                    //
                    break;
                case TrainingProposal::STATUS_RESERVED:
                    //
                    break;
                case TrainingProposal::STATUS_CONFIRMATION_PENDING:
                    //
                    break;
                case TrainingProposal::STATUS_CONFIRMED:
                    //
                    break;
            }
        }
    }

    /**
     * @param TrainingProposal $trainingProposal
     * @return void
     */
    public function deleted(TrainingProposal $trainingProposal)
    {
        //
    }

    /**
     * @param TrainingProposal $trainingProposal
     * @return void
     */
    public function restored(TrainingProposal $trainingProposal)
    {
        //
    }

    /**
     * @param TrainingProposal $trainingProposal
     * @return void
     */
    public function forceDeleted(TrainingProposal $trainingProposal)
    {
        //
    }
}
