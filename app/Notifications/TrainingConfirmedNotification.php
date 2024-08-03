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
 * Training Confirmed Notification
 *
 * @package \App\Notifications
 */
class TrainingConfirmedNotification extends Notification
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
            ->subject(__('notifications.training_confirmed.subject', [
                'first_date'  => $this->args['first_date'],
                'second_date' => $this->args['second_date'],
            ]))
            ->greeting(__('notifications.training_confirmed.greeting'))
            ->line(__('notifications.training_confirmed.first_line'))
            ->line(__('notifications.training_confirmed.second_line', [
                'first_date'                     => $this->args['first_date'],
                'first_date_start_and_end_time'  => $this->args['first_date_start_and_end_time'],
                'second_date'                    => $this->args['second_date'],
                'second_date_start_and_end_time' => $this->args['second_date_start_and_end_time'],
                'location'                       => $this->args['location'],
                'multiplier_name'                => $this->args['multiplier_name'],
            ]))
            ->salutation(__('notifications.training_confirmed.salutation'));
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
