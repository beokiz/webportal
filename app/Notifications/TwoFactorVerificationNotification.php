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
 * Two-Factor Verification Notification
 *
 * @package \App\Notifications
 */
class TwoFactorVerificationNotification extends Notification
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
        $verificationCode = decrypt(session('2fa_data.code'));

        return (new CustomMailMessage)
            ->subject(__('notifications.2fa_verification.subject'))
            ->line(__('notifications.2fa_verification.first_line'))
            ->line(__('notifications.2fa_verification.second_line', [
                'code' => $verificationCode,
            ]))
            ->action(__('notifications.2fa_verification.action_text'), route('2fa.create', [
                'two_factor_code' => $verificationCode,
            ]));
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
