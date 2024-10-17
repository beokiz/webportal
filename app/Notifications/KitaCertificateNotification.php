<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Notifications;

use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\File;

/**
 * Kita Certificate Notification
 *
 * @package \App\Notifications
 */
class KitaCertificateNotification extends Notification
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
        $kita     = $this->data['kita'];
        $filePath = $this->data['file_path'];

        $fileName = basename($filePath);
        $mimeType = File::mimeType($filePath);

        return (new CustomMailMessage)
            ->subject(__('notifications.kita_certificate.subject'))
            ->greeting(__('notifications.kita_certificate.greeting'))
            ->line(__('notifications.kita_certificate.first_line'))
            ->line(__('notifications.kita_certificate.second_line'))
            ->line(__('notifications.kita_certificate.third_line'))
            ->salutation(__('notifications.kita_certificate.salutation'))
            ->attach($filePath, [
                'as'   => $fileName,
                'mime' => $mimeType,
            ]);
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
