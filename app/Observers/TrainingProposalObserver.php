<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Observers;

use App\Models\Kita;
use App\Models\Training;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Services\Items\TrainingItemService;
use Illuminate\Support\Str;

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
            $trainingItemService = app(TrainingItemService::class);

            $trainingProposal->loadMissing(['multiplier', 'kitas.trainingProposals', 'trainingProposalConfirmations']);

            $notificationData = $trainingProposal->getNotificationsData();

            $roles = config('permission.project_roles');

            switch ($trainingProposal->status) {
                case TrainingProposal::STATUS_EMAIL_NOT_CONFIRMED:
                    //
                    break;
                case TrainingProposal::STATUS_OPEN:
                    $trainingProposal->kitas->each(function ($kita) use ($trainingProposal) {
                        $kita->trainingProposals()
                            ->where('id', '!=', $trainingProposal->id)
                            ->where('status', TrainingProposal::STATUS_OBSOLETE)
                            ->update(['status' => TrainingProposal::STATUS_OPEN]);
                    });
                    break;
                case TrainingProposal::STATUS_RESERVED:
                    $trainingProposal->kitas->each(function ($kita) use ($trainingProposal) {
                        $kita->trainingProposals()
                            ->where('id', '!=', $trainingProposal->id)
                            ->where('status', TrainingProposal::STATUS_OPEN)
                            ->update(['status' => TrainingProposal::STATUS_OBSOLETE]);
                    });
                    break;
                case TrainingProposal::STATUS_OBSOLETE:
                    //
                    break;
                case TrainingProposal::STATUS_CONFIRMATION_PENDING:
                    $trainingProposalConfirmation = null;

                    // Send kitas managers notifications
                    $trainingProposal->kitas->each(function (Kita $kita) use ($trainingProposal, $notificationData, $roles, &$trainingProposalConfirmation) {
                        $trainingProposalConfirmation = $kita->trainingProposalConfirmations()->create([
                            'training_proposal_id' => $trainingProposal->id,
                            'confirmed'            => false,
                            'token'                => Str::random(20),
                        ]);

                        $kita->users()->whereHas('roles', function ($query) use ($roles) {
                            $query->where('name', $roles['manager']);
                        })->get()->each(function (User $user) use ($trainingProposal, $notificationData, $trainingProposalConfirmation) {
                            $user->sendTrainingProposalConfirmationPendingNotification(array_merge($notificationData, [
                                'is_copy'           => false,
                                'confirmation_link' => route('training_proposals.confirm', [$trainingProposal->id, 'token' => $trainingProposalConfirmation->token]),
                            ]));
                        });
                    });

                    // Additionally, we send a notification to the multiplier user
                    if (!empty($trainingProposal->multiplier) && !empty($trainingProposalConfirmation)) {
                        $trainingProposal->multiplier->sendTrainingProposalConfirmationPendingNotification(array_merge($notificationData, [
                            'is_copy'           => true,
                            'confirmation_link' => route('training_proposals.confirm', [$trainingProposal->id, 'token' => $trainingProposalConfirmation->token]),
                        ]));
                    }
                    break;
                case TrainingProposal::STATUS_CONFIRMED:
                    // Create a new Training proposal confirmation if it has not been created before
                    $kitaLocations    = [];
                    $kitaStreets      = [];
                    $kitaHouseNumbers = [];
                    $kitaZipCodes     = [];
                    $kitaCities       = [];

                    $trainingProposal->kitas->each(function (Kita $kita) use ($trainingProposal, &$kitaLocations, &$kitaStreets, &$kitaHouseNumbers, &$kitaZipCodes, &$kitaCities) {
//                        $kitaLocations[] = implode(', ', array_filter([
//                            trim("{$kita->street} {$kita->house_number}"),
//                            $kita->district,
//                            $kita->zip_code,
//                            $kita->city,
//                        ]));

                        $kitaStreets[]      = $kita->street;
                        $kitaHouseNumbers[] = $kita->house_number;
                        $kitaZipCodes[]     = $kita->zip_code;
                        $kitaCities[]       = $kita->city;

                        $trainingProposalConfirmation = $trainingProposal->trainingProposalConfirmations
                            ->where('training_proposal_id', $trainingProposal->id)
                            ->where('kita_id', $kita->id)
                            ->first();

                        if (empty($trainingProposalConfirmation)) {
                            $kita->trainingProposalConfirmations()->create([
                                'training_proposal_id' => $trainingProposal->id,
                                'confirmed'            => true,
                                'token'                => Str::random(20),
                            ]);
                        } else {
//                            $trainingProposalConfirmation->update([
//                                'confirmed' => true,
//                            ]);
                        }
                    });

                    // Create new Training based on Training proposal
                    $addressPlaceholder = __('crud.trainings.choose_location');

                    if (!empty($kitaLocations)) {
                        $location = count($kitaLocations) === 1 ? $kitaLocations[0] : $addressPlaceholder;
                    } else {
                        $location = $addressPlaceholder;
                    }

                    if (!empty($kitaStreets)) {
                        $street = count($kitaStreets) === 1 ? $kitaStreets[0] : $addressPlaceholder;
                    } else {
                        $street = $addressPlaceholder;
                    }

                    if (!empty($kitaHouseNumbers)) {
                        $houseNumber = count($kitaHouseNumbers) === 1 ? $kitaHouseNumbers[0] : $addressPlaceholder;
                    } else {
                        $houseNumber = $addressPlaceholder;
                    }

                    if (!empty($kitaZipCodes)) {
                        $zipCode = count($kitaZipCodes) === 1 ? $kitaZipCodes[0] : $addressPlaceholder;
                    } else {
                        $zipCode = $addressPlaceholder;
                    }

                    if (!empty($kitaCities)) {
                        $city = count($kitaCities) === 1 ? $kitaCities[0] : $addressPlaceholder;
                    } else {
                        $city = $addressPlaceholder;
                    }

                    $training = $trainingItemService->create([
                        'training_proposal_id'           => $trainingProposal->id,
                        'multi_id'                       => $trainingProposal->multiplier->id,
                        'first_date'                     => $trainingProposal->first_date,
                        'first_date_start_and_end_time'  => null,
                        'second_date'                    => $trainingProposal->second_date,
                        'second_date_start_and_end_time' => null,
                        'max_participant_count'          => $trainingProposal->participant_count,
                        'participant_count'              => $trainingProposal->participant_count,
                        'type'                           => Training::TYPE_IN_HOUSE,
                        'status'                         => Training::STATUS_CONFIRMED,
                        'location'                       => !empty($trainingProposal->location) ? $trainingProposal->location : $location,
                        'street'                         => !empty($trainingProposal->street) ? $trainingProposal->street : $street,
                        'house_number'                   => !empty($trainingProposal->house_number) ? $trainingProposal->house_number : $houseNumber,
                        'zip_code'                       => !empty($trainingProposal->zip_code) ? $trainingProposal->zip_code : $zipCode,
                        'city'                           => !empty($trainingProposal->city) ? $trainingProposal->city : $city,
                        'notes'                          => $trainingProposal->notes,
                    ]);

//                    if (!empty($training)) {
//                        $trainingItemService->updateAttachedKitas(
//                            $training->id,
//                            $trainingProposal->kitas->pluck('id')->toArray()
//                        );
//                    }
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
