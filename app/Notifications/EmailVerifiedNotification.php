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
        $kitas = $notifiable->kitas;

        // Prüfen, ob es sich um eine zusammengelegte Schulung handelt
        $isMergedTraining = $kitas->first() && $kitas->first()->num_pedagogical_staff <= 10;

        // Subjekt basierend auf dem Szenario auswählen
        $subjectKey = $isMergedTraining
            ? 'notifications.email_verified.subject.training_confirmation'
            : 'notifications.email_verified.subject.training_proposal';

        if ($isMergedTraining) {
            // Trainings der Kita laden und formatieren
            $trainingDetailsFormatted = $kitas->first()->trainings->isNotEmpty()
                ? '<ul>' . implode('', $kitas->first()->trainings->map(function ($training) {
                    $data = $training->getNotificationsData();
                    return sprintf(
                        "<li>Erster Schulungstag: %s (%s)</li><li>Zweiter Schulungstag: %s (%s)</li><li>Ort: %s</li>",
                        $data['first_date'],
                        $data['first_date_start_and_end_time'],
                        $data['second_date'],
                        $data['second_date_start_and_end_time'],
                        $data['location']
                    );
                })->toArray()) . '</ul>'
                : "<p>Noch keine Details verfügbar.</p>";

            return (new CustomMailMessage)
                ->subject(__($subjectKey))
                ->greeting(__('notifications.email_verified.greeting', [
                    'name' => $notifiable->full_name,
                ]))
                ->line(__('Ihre Schulung ist hiermit bestätigt.'))
                ->line(__('Hier sind die Details:') . $trainingDetailsFormatted)
                ->line(__('Wir freuen uns auf Sie und Ihr Team.'))
                ->salutation(__('notifications.email_verified.salutation'));
        }

        // Trainingsproposals aller Kitas sammeln und formatieren
        $trainingProposalDetailsFormatted = $kitas->flatMap(function ($kita) {
            return $kita->trainingProposals->map(function ($proposal, $index) {
                return __(
                    $index > 0
                        ? 'notifications.email_verified.other_training_item'
                        : 'notifications.email_verified.first_training_item',
                    [
                        'first_date'  => $proposal->first_date->format('d.m.Y'),
                        'second_date' => $proposal->second_date->format('d.m.Y'),
                    ]
                );
            });
        })->isNotEmpty()
            ? '<ul>' . implode('', $kitas->flatMap(function ($kita) {
                return $kita->trainingProposals->map(function ($proposal, $index) {
                    return sprintf("<li>%s</li>", __(
                        $index > 0
                            ? 'notifications.email_verified.other_training_item'
                            : 'notifications.email_verified.first_training_item',
                        [
                            'first_date'  => $proposal->first_date->format('d.m.Y'),
                            'second_date' => $proposal->second_date->format('d.m.Y'),
                        ]
                    ));
                });
            })->toArray()) . '</ul>'
            : "<p>Noch keine Terminvorschläge verfügbar.</p>";

        // Standard-Mail für Terminvorschläge
        return (new CustomMailMessage)
            ->subject(__($subjectKey))
            ->greeting(__('notifications.email_verified.greeting', [
                'name' => $notifiable->full_name,
            ]))
            ->line(__('notifications.email_verified.first_line', [
                'training_proposals' => $trainingProposalDetailsFormatted,
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
