<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Observers;

use App\Models\Kita;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;

/**
 * Observer for Kita Model
 *
 * @package \App\Observers
 */
class KitaObserver extends BaseObserver
{
    /**
     * @var PasswordBroker
     */
    protected $tokens;

    /**
     * KitaObserver constructor.
     *
     * @return void
     */
    public function __construct(PasswordBroker $factory)
    {
        $this->tokens = $factory->getRepository();

        parent::__construct();
    }

    /**
     * @param Kita $kita
     * @return void
     */
    public function created(Kita $kita)
    {
        //
    }

    /**
     * @param Kita $kita
     * @return void
     */
    public function updated(Kita $kita)
    {
        if ($kita->isDirty('approved') && !empty($kita->approved)) {
            $roles = config('permission.project_roles');

            // Send kitas managers notifications
            $kita->users()->whereHas('roles', function ($query) use ($roles) {
                $query->where('name', $roles['manager']);
            })->get()->each(function (User $user) {
                // If the field 'first_login_at' is empty - we send the user a letter to install credentials
                if (empty($user->first_login_at)) {
                    $user->sendWelcomeNotification(
                        $this->tokens->create($user)
                    );
                }
            });
        }
    }

    /**
     * @param Kita $kita
     * @return void
     */
    public function deleted(Kita $kita)
    {
        //
    }

    /**
     * @param Kita $kita
     * @return void
     */
    public function restored(Kita $kita)
    {
        //
    }

    /**
     * @param Kita $kita
     * @return void
     */
    public function forceDeleted(Kita $kita)
    {
        //
    }
}
