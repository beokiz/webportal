<?php

namespace App\Notifications;

use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Email Verified Notification
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
        // Formatieren der Schulungsdaten
        $trainingDetails = !empty($this->data) && is_array($this->data)
            ? implode("\n", array_map(function ($training) {
                return "• " . $training;
            }, $this->data))
            : "Noch keine Details verfügbar.";

        $trainingDetailsFormatted = nl2br($trainingDetails); // Zeilenumbrüche zu <br/>

        // Loggen der bereitgestellten Daten
        Log::info('Benachrichtigung: EmailVerifiedNotification', [
            'notifiable' => $notifiable,
            'data' => $this->data,
            'training_details' => $trainingDetails,
        ]);

        // Erstellen der E-Mail
        $mailMessage = (new CustomMailMessage)
            ->subject(__('notifications.email_verified.subject'))
            ->greeting(__('notifications.email_verified.greeting', [
                'name' => $notifiable->full_name,
            ]))
            ->line(__('Ihre Schulung ist hiermit bestätigt.'))
            ->line(__('Hier sind die Details:') . '<br/>' . $trainingDetailsFormatted)
            ->line(__('Wir freuen uns auf Sie und Ihr Team.'))
            ->salutation(__('notifications.email_verified.salutation'));

        // Finalen E-Mail-Inhalt loggen
        Log::info('Finaler E-Mail-Inhalt:', [
            'subject' => __('notifications.email_verified.subject'),
            'greeting' => __('notifications.email_verified.greeting', [
                'name' => $notifiable->full_name,
            ]),
            'lines' => [
                __('Ihre Schulung ist hiermit bestätigt.'),
                __('Hier sind die Details:') . '<br/>' . $trainingDetailsFormatted,
                __('Wir freuen uns auf Sie und Ihr Team.'),
            ],
            'salutation' => __('notifications.email_verified.salutation'),
        ]);

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
