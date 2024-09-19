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
 * Email Verified Notification
 *
 * @package \App\Notifications
 */
class EmailVerifiedNotification extends Notification
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
        if (is_array($this->data)) {
            $trainingProposals = count($this->data) > 1
                ? "\n &#x2022; " . implode("\n &#x2022; ", (array) $this->data)
                : "\n &#x2022; {$this->data[0]}";
        } else {
            $trainingProposals = "-";
        }

        $trainingProposals = nl2br($trainingProposals);

        return (new CustomMailMessage)
            ->subject(__('notifications.email_verified.subject'))
            ->greeting(__('notifications.email_verified.greeting', [
                'name' => $notifiable->full_name,
            ]))
            ->line(__('notifications.email_verified.first_line', ['training_proposals' => $trainingProposals]))
            ->line(__('notifications.email_verified.second_line'))
            ->salutation(__('notifications.email_verified.salutation'));
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
