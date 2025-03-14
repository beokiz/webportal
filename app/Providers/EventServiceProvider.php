<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Providers;

use App\Listeners\TriggerUserVerifiedEmailEvent;
use App\Models\DownloadableFile;
use App\Models\Kita;
use App\Models\Training;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Observers\DownloadableFileObserver;
use App\Observers\KitaObserver;
use App\Observers\TrainingObserver;
use App\Observers\TrainingProposalObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Event Service Provider
 *
 * @package \App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Verified::class   => [
//            SendWelcomeNotification::class,
            TriggerUserVerifiedEmailEvent::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        DownloadableFile::observe(DownloadableFileObserver::class);
        Kita::observe(KitaObserver::class);
        Training::observe(TrainingObserver::class);
        TrainingProposal::observe(TrainingProposalObserver::class);
        User::observe(UserObserver::class);
    }
}
