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
use Illuminate\Support\Facades\Log;

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
        try {
            Log::info('Preparing certificate email for user.', [
                'user_id' => $notifiable->id ?? null,
                'user_email' => $notifiable->email ?? null,
                'file_path' => $this->data['file_path'] ?? 'File path not set',
            ]);

            $filePath = $this->data['file_path'];

            if (!file_exists($filePath)) {
                Log::error('Certificate file not found.', ['file_path' => $filePath]);
                throw new \Exception('Certificate file does not exist.');
            }

            $fileName = basename($filePath);
            $mimeType = File::mimeType($filePath);

            Log::info('Certificate file details retrieved.', [
                'file_name' => $fileName,
                'mime_type' => $mimeType,
            ]);

            return (new CustomMailMessage)
                ->subject(__('notifications.kita_certificate.subject'))
                ->greeting(__('notifications.kita_certificate.greeting', ['full_name' => $notifiable->full_name]))
                ->line(__('notifications.kita_certificate.first_line'))
                ->line(__('notifications.kita_certificate.second_line'))
                ->line(__('notifications.kita_certificate.third_line'))
                ->line(__('notifications.kita_certificate.fourth_line'))
                ->line(__('notifications.kita_certificate.fifth_line'))
                ->line(__('notifications.kita_certificate.sixth_line'))
                ->line(__('notifications.kita_certificate.seventh_line'))
                ->line(__('notifications.kita_certificate.zoom_title'))
                ->line(__('notifications.kita_certificate.zoom_link'))
                ->line(__('notifications.kita_certificate.zoom_meeting_id'))
                ->line(__('notifications.kita_certificate.zoom_password'))
                ->salutation(__('notifications.kita_certificate.salutation'))
                ->attach($filePath, [
                    'as' => $fileName,
                    'mime' => $mimeType,
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to send certificate email.', [
                'user_id' => $notifiable->id ?? null,
                'user_email' => $notifiable->email ?? null,
                'error_message' => $e->getMessage(),
            ]);

            // Optionally rethrow the exception if needed
            throw $e;
        }
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
