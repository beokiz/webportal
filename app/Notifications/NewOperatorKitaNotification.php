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
 * New Operator Kita Notification
 *
 * @package \App\Notifications
 */
class NewOperatorKitaNotification extends Notification
{
    use Queueable;

    /**
     * @var array|null
     */
    public ?array $data;

    /**
     * Create a new notification instance.
     *
     * @param array|null $data
     * @return void
     */
    public function __construct(?array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return CustomMailMessage
     */
    public function toMail($notifiable)
    {
        return (new CustomMailMessage)
            ->subject(__('notifications.new_operator_kita.subject', [
                'kita_name' => $this->data['kita_name'],
            ]))
            ->greeting(__('notifications.new_operator_kita.greeting', [
                'operator_name' => $this->data['operator_name'],
            ]))
            ->line(__('notifications.new_operator_kita.first_line', [
                'kita_name' => $this->data['kita_name'],
            ]))
            ->line(__('notifications.new_operator_kita.second_line'))
            ->line(__('notifications.new_operator_kita.third_line'))
            ->salutation(__('notifications.new_operator_kita.salutation'));
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
