<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Console;

use App\Jobs\MakeDatabaseBackupJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Console Kernel
 *
 * @package \App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->job(new ResetKitasYearlyEvaluationReminderJob())->dailyAt('12:00');

        // $schedule->job(new SendYearlyEvaluationReminderNotificationJob())->hourly();

        // $schedule->job(new ResetKitasYearlyEvaluationReminderJob())->dailyAt('12:00');

        // Remove project temporary files
        $schedule->command('temp:files:clean')->hourly();

        // Make daily DB backup & clean old DB backups
        $schedule->job(new MakeDatabaseBackupJob())->dailyAt('01:00');
        $schedule->command('clean:old-backups')->dailyAt('01:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
