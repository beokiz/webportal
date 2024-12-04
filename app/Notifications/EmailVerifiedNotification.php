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
        // Ermitteln der ersten Kita des Benutzers
        $kita = $notifiable->kitas->first();

        // Prüfen, ob es sich um eine zusammengelegte Schulung handelt
        $isMergedTraining = $kita && $kita->num_pedagogical_staff <= 10;

        // Schulungsdaten für zusammengelegte Schulungen
        if ($isMergedTraining) {
            $trainings = $notifiable->trainings;

            // Logging der Trainingsdaten
            if ($trainings->isNotEmpty()) {
                Log::info('Trainings des Benutzers:', [
                    'user_id' => $notifiable->id,
                    'trainings' => $trainings->map->getNotificationsData(),
                ]);
            } else {
                Log::warning('Keine Trainings für den Benutzer gefunden.', [
                    'user_id' => $notifiable->id,
                ]);
            }

            // Formatieren der Trainingsdetails
            $trainingDetails = $trainings->isNotEmpty()
                ? implode("\n", $trainings->map(function ($training) {
                    $data = $training->getNotificationsData();
                    Log::info('Training-Daten:', $data);
                    return sprintf(
                        "• %s\n  Datum 1: %s (%s)\n  Datum 2: %s (%s)\n  Ort: %s",
                        $data['multiplier_name'] ?? 'Training',
                        $data['first_date'],
                        $data['first_date_start_and_end_time'],
                        $data['second_date'],
                        $data['second_date_start_and_end_time'],
                        $data['location']
                    );
                })->toArray())
                : "Noch keine Details verfügbar.";

            $trainingDetailsFormatted = nl2br($trainingDetails);

            Log::info('Formatiertes Trainings-Details:', [
                'training_details' => $trainingDetailsFormatted,
            ]);

            return (new CustomMailMessage)
                ->subject(__('notifications.email_verified.subject'))
                ->greeting(__('notifications.email_verified.greeting', [
                    'name' => $notifiable->full_name,
                ]))
                ->line(__('Ihre Schulung ist hiermit bestätigt.'))
                ->line(__('Hier sind die Details:') . '<br/>' . $trainingDetailsFormatted)
                ->line(__('Wir freuen uns auf Sie und Ihr Team.'))
                ->salutation(__('notifications.email_verified.salutation'));
        }

        // Standard-Mail für andere Schulungen
        if (!empty($this->data) && is_array($this->data)) {
            $trainingProposals = count($this->data) > 1
                ? "\n &#x2022; " . implode("\n &#x2022; ", (array) $this->data)
                : "\n &#x2022; {$this->data[0]}";
        } else {
            $trainingProposals = "-";
        }

        $trainingProposals = nl2br($trainingProposals);

        Log::info('Standard-Mail wird gesendet.', [
            'user_id' => $notifiable->id,
            'training_proposals' => $trainingProposals,
        ]);

        return (new CustomMailMessage)
            ->subject(__('notifications.email_verified.subject'))
            ->greeting(__('notifications.email_verified.greeting', [
                'name' => $notifiable->full_name,
            ]))
            ->line(__('notifications.email_verified.first_line', [
                'training_proposals' => $trainingProposals,
            ]))
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
        return [];
    }
}
