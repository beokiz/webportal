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
 * Password Changed Notification
 *
 * @package \App\Notifications
 */
class PasswordChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
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
     * @return CustomMailMessage
     */
    public function toMail($notifiable)
    {
        return (new CustomMailMessage)
            ->subject(__('notifications.password_changed.subject'))
            ->greeting(__('notifications.password_changed.greeting', ['name' => $notifiable->full_name]))
            ->line(__('notifications.password_changed.first_line'))
            ->line(__('notifications.password_changed.second_line', [
                'support_email' => config('app.emails.support'),
            ]))
            ->line(__('notifications.password_changed.third_line'))
            ->line(__('notifications.password_changed.fourth_line'));
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
