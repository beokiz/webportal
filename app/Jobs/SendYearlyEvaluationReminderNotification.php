<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych, Pavel Karpushevskiy
 */

namespace App\Jobs;

use App\Models\Kita;
use App\Models\Setting;
use App\Models\SurveyTimePeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Send Yearly Evaluation Reminder Notification
 *
 * @package \App\Jobs
 */
class SendYearlyEvaluationReminderNotification implements ShouldQueue
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
        $currentDate = Carbon::now();

        $currentYearSurveyTimePeriod = SurveyTimePeriod::where('year', $currentDate->year)->first();

        if ($currentYearSurveyTimePeriod) {
            $sendYearlyEvaluationReminderNtfBeforeDays      = Setting::where('name', 'send_yearly_evaluation_reminder_ntf_before_days')->first();
            $sendYearlyEvaluationReminderNtfBeforeDaysValue = !empty($sendYearlyEvaluationReminderNtfBeforeDays->value)
                ? $sendYearlyEvaluationReminderNtfBeforeDays->value
                : config('beokiz.default_send_yearly_evaluation_reminder_ntf_before_days');

            if (
                $currentDate->diffInDays($currentYearSurveyTimePeriod->survey_end_date) <= $sendYearlyEvaluationReminderNtfBeforeDaysValue &&
                $currentDate->lte($currentYearSurveyTimePeriod->survey_end_date)
            ) {
                $kitas = Kita::where('is_yearly_evaluation_reminder_ntf_sent', false)
                    ->whereDoesntHave('yearlyEvaluations', function ($query) use ($currentDate) {
                        $query->where('year', '=', $currentDate->year);
                    })
                    ->with(['users'])
                    ->get();

                $kitas->each(function ($kita) use ($currentDate, $currentYearSurveyTimePeriod) {
                    $kita->users->each(function ($user) use ($currentDate, $currentYearSurveyTimePeriod, $kita) {
                        $user->sendYearlyEvaluationReminderNotification([
                            'evaluation_year'   => $currentDate->year,
                            'survey_start_date' => $currentYearSurveyTimePeriod->survey_start_date->format('d.m.Y'),
                            'survey_end_date'   => $currentYearSurveyTimePeriod->survey_end_date->format('d.m.Y'),
                        ]);
                    });

                    $kita->is_yearly_evaluation_reminder_ntf_sent = true;

                    $kita->save();
                });
            }
        }
    }
}
