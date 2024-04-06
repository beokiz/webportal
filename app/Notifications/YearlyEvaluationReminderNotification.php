<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Notifications;

use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Yearly Evaluation Reminder Notification
 *
 * @package \App\Notifications
 */
class YearlyEvaluationReminderNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    public $args;

    /**
     * Create a new notification instance.
     *
     * @param array $args
     * @return void
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @link https://tighten.com/blog/opting-out-a-simple-solution-for-conditionally-cancelling-laravel-notifications/
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new CustomMailMessage)
            ->subject(__('notifications.yearly_evaluation_reminder.subject'))
            ->greeting(__('notifications.yearly_evaluation_reminder.greeting'))
            ->line(__('notifications.yearly_evaluation_reminder.first_line', [
                'evaluation_year' => $this->args['evaluation_year'],
                'survey_end_date' => $this->args['survey_end_date'],
            ]))
            ->line(__('notifications.yearly_evaluation_reminder.second_line', [
                'survey_end_date' => $this->args['survey_end_date'],
                'site'            => str_replace(['http://', 'https://'], '', config('app.url')),
            ]))
            ->line(__('notifications.yearly_evaluation_reminder.third_line'))
            ->salutation(__('notifications.yearly_evaluation_reminder.salutation'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
