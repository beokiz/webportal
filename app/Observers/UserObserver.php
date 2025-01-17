<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Observers;

use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;

/**
 * Observer for User Model
 *
 * @package \App\Observers
 */
class UserObserver extends BaseObserver
{
    /**
     * @var PasswordBroker
     */
    protected $tokens;

    /**
     * UserObserver constructor.
     *
     * @return void
     */
    public function __construct(PasswordBroker $factory)
    {
        $this->tokens = $factory->getRepository();

        parent::__construct();
    }

    /**
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        // Hole den aktuellen Benutzer
        $currentUser = auth()->user();

        // Prüfen, ob der aktuelle Benutzer existiert und die Rolle "Multiplikator" oder "Admin" hat
        if ($currentUser && (
            $currentUser->hasRole(config('permission.project_roles.user_multiplier')) ||
            $currentUser->hasRole(config('permission.project_roles.super_admin')) ||
            $currentUser->hasRole(config('permission.project_roles.admin'))
        )) {
            return;
        }

        // Wenn der aktuelle Benutzer kein Multiplikator ist, Willkommensnachricht senden
        $user->sendWelcomeNotification(
            $this->tokens->create($user)
        );
    }

    /**
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->isDirty('password')) {
            $user->sendPasswordChangedNotification();
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
