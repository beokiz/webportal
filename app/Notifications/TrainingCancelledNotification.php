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
 * Training Cancelled Notification
 *
 * @package \App\Notifications
 */
class TrainingCancelledNotification extends Notification
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
            ->subject(__('notifications.training_cancelled.subject'))
            ->greeting(__('notifications.training_cancelled.greeting'))
            ->line(__('notifications.training_cancelled.first_line', [
                'first_date'  => $this->args['first_date'],
                'second_date' => $this->args['second_date'],
            ]))
            ->line(__('notifications.training_cancelled.second_line'))
            ->salutation(__('notifications.training_cancelled.salutation'));
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
