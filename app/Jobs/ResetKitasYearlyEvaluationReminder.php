<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych, Pavel Karpushevskiy
 */

namespace App\Jobs;

use App\Models\Kita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Reset Kitas Yearly Evaluation Reminder
 *
 * @package \App\Jobs
 */
class ResetKitasYearlyEvaluationReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        Kita::query()->update([
            'is_yearly_evaluation_reminder_ntf_sent' => false,
        ]);
    }
}
