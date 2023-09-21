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
     * @var \Illuminate\Auth\Passwords\TokenRepositoryInterface
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
        //
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
