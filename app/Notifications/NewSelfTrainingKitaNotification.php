<?php
/*
 * BeoKiz Team
 * Copyright (c) 2024
 */

namespace App\Notifications;

use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * New Training Request Notification
 *
 * @package App\Notifications
 */
class NewSelfTrainingKitaNotification extends Notification
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
            ->subject(__('notifications.email_new_selftraining_kita.subject', [
                'app_name' => config('app.name'),
                'kita_name' => $this->data['kita_name'] ?? '',
            ]))
            ->greeting(__('notifications.email_new_selftraining_kita.greeting', [
                'multi_name' => $this->data['multi_name'] ?? 'Nutzer',
            ]))
            ->line(__('notifications.email_new_selftraining_kita.first_line', [
                'kita_name' => $this->data['kita_name'] ?? '',
            ]))
            ->line(__('notifications.email_new_selftraining_kita.second_line'))
            ->line("<ul style=\"list-style-type:disc; margin-left:20px;\">")
            ->line("<li><strong>Kitanummer:</strong> {$this->data['kita_number']}</li>")
            ->line("<li><strong>Einrichtungsname:</strong> {$this->data['kita_name']}</li>")
            ->line("<li><strong>Anschrift:</strong> {$this->data['kita_address']}</li>")
            ->line("<li><strong>Ansprechpartner:in:</strong> {$this->data['manager_name']}</li>")
            ->line("<li><strong>E-Mail:</strong> {$this->data['manager_email']}</li>")
            ->line("<li><strong>Telefon:</strong> {$this->data['manager_phone']}</li>")
            ->line("<li><strong>Anmerkungen der Kita:</strong> {$this->data['kita_remarks']}</li>")
            ->line("</ul>")
            ->line("Bitte vergiss nicht, die Schulung im Portal anzulegen.")
            ->salutation("Danke und beste Grüße,\n\ndas BeoKiz-Team");
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
            'kita_name' => $this->data['kita_name'],
            'kita_number' => $this->data['kita_number'],
            'kita_address' => $this->data['kita_address'],
            'manager_name' => $this->data['manager_name'],
            'manager_email' => $this->data['manager_email'],
            'manager_phone' => $this->data['manager_phone'],
            'kita_remarks' => $this->data['kita_remarks'],
        ];
    }
}
