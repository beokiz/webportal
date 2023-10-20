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
 * Connected To Kitas Notification
 *
 * @package \App\Notifications
 */
class ConnectedToKitasNotification extends Notification
{
    use Queueable;

    /**
     * @var array|null
     */
    public ?array $kitas;

    /**
     * Create a new notification instance.
     *
     * @param array|null $kitas
     * @return void
     */
    public function __construct(?array $kitas)
    {
        $this->kitas = $kitas;
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
        if (is_array($this->kitas)) {
            $kitas = count($this->kitas) > 1
                ? "\n &#x2022; " . implode("\n &#x2022; ", (array) $this->kitas)
                : "\n &#x2022; {$this->kitas[0]}";
        } else {
            $kitas = "\n &#x2022; {$this->kitas}";
        }

        $kitas = nl2br($kitas);

        return (new CustomMailMessage)
            ->subject(__('notifications.connected_to_kitas.subject'))
            ->greeting(__('notifications.connected_to_kitas.greeting', ['name' => $notifiable->full_name]))
            ->line(__('notifications.connected_to_kitas.first_line', ['kitas' => $kitas]))
            ->line(__('notifications.connected_to_kitas.second_line'))
            ->line(__('notifications.connected_to_kitas.third_line', ['support_email' => config('app.emails.support')]));
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
