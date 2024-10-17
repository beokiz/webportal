<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Observers;

use App\Models\Kita;
use App\Models\Training;
use App\Models\User;
use App\Services\Items\KitaItemService;
use Illuminate\Auth\Passwords\PasswordBroker;

/**
 * Observer for Training Model
 *
 * @package \App\Observers
 */
class TrainingObserver extends BaseObserver
{
    /**
     * @var PasswordBroker
     */
    protected $tokens;

    /**
     * TrainingObserver constructor.
     *
     * @return void
     */
    public function __construct(PasswordBroker $factory)
    {
        $this->tokens = $factory->getRepository();

        parent::__construct();
    }

    /**
     * @param Training $training
     * @return void
     */
    public function created(Training $training)
    {
        //
    }

    /**
     * @param Training $training
     * @return void
     */
    public function updated(Training $training)
    {
        if ($training->isDirty('status')) {
            $training->loadMissing(['multiplier']);

            $notificationData = $training->getNotificationsData();

            $roles = config('permission.project_roles');

            switch ($training->status) {
                case Training::STATUS_PLANNED:
                    //
                    break;
                case Training::STATUS_CONFIRMED:
                    //
                    break;
                case Training::STATUS_COMPLETED:
                    $kitaItemService = app(KitaItemService::class);

                    $training->loadMissing(['kitas.users']);

                    if (!empty($training->kitas)) {
                        // Update related kitas
                        $training->kitas()->update([
                            'approved' => true,
                        ]);

                        // Send kitas managers notifications
                        $training->kitas->each(function (Kita $kita) use ($kitaItemService, $training, $notificationData, $roles) {
                            $kita->users()->whereHas('roles', function ($query) use ($roles) {
                                $query->where('name', $roles['manager']);
                            })->get()->each(function (User $user) use ($kitaItemService, $training, $kita, $notificationData) {
                                $user->sendTrainingCompletedNotification($notificationData);

                                // Send certificate to kita managers
                                $pdfFile = $kitaItemService->generatePdfCertificate($kita->id);

                                if (!empty($pdfFile)) {
                                    $user->sendTrainingCompletedNotification([
                                        'kita'      => $kita,
                                        'file_path' => $pdfFile,
                                    ]);
                                }

                                // If the field 'first_login_at' is empty - we send the user a letter to install credentials
                                if (empty($user->first_login_at)) {
                                    $user->sendWelcomeNotification(
                                        $this->tokens->create($user)
                                    );
                                }
                            });
                        });
                    }
                    break;
                case Training::STATUS_CANCELLED:
                    // Send kitas managers notifications
                    $training->kitas->each(function (Kita $kita) use ($training, $notificationData, $roles) {
                        $kita->users()->whereHas('roles', function ($query) use ($roles) {
                            $query->where('name', $roles['manager']);
                        })->get()->each(function (User $user) use ($training, $notificationData) {
                            $user->sendTrainingCancelledNotification($notificationData);
                        });
                    });
                    break;
            }
        }
    }

    /**
     * @param Training $training
     * @return void
     */
    public function deleted(Training $training)
    {
        //
    }

    /**
     * @param Training $training
     * @return void
     */
    public function restored(Training $training)
    {
        //
    }

    /**
     * @param Training $training
     * @return void
     */
    public function forceDeleted(Training $training)
    {
        //
    }
}
