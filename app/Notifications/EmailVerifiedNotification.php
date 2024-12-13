<?php

namespace App\Notifications;

use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
            ? __('notifications.email_verified.merged.subject')
            : __('notifications.email_verified.default.subject');

        $lines = $isMergedTraining
            ? $this->buildMergedTrainingLines($kitas)
            : $this->buildDefaultTrainingLines($kitas);

        // Build the message
        $mailMessage = new CustomMailMessage();
        $mailMessage->subject($subjectKey)
            ->greeting(__('notifications.email_verified.common.greeting', [
                'name' => $notifiable->full_name,
            ]));

        foreach ($lines as $line) {
            $mailMessage->line($line);
        }

        return $mailMessage->salutation(__('notifications.email_verified.common.salutation'));
    }

    private function buildMergedTrainingLines($kitas): array
    {
        $trainingDetails = $kitas->first()->trainings->isNotEmpty()
            ? $this->formatTrainingDetails($kitas)
            : __('notifications.email_verified.merged.no_details');

        return [
            __('notifications.email_verified.merged.confirmation'),
            __('notifications.email_verified.merged.details_header') . $trainingDetails,
            __('notifications.email_verified.merged.closing'),
        ];
    }

    private function buildDefaultTrainingLines($kitas): array
    {
        $trainingProposalDetails = $this->formatTrainingProposals($kitas);

        return [
            __('notifications.email_verified.default.first_line', [
                'training_proposals' => $trainingProposalDetails,
            ]),
            __('notifications.email_verified.default.second_line'),
        ];
    }

    private function formatTrainingDetails($kitas): string
    {
        return '<ul>' . implode('', $kitas->first()->trainings->map(function ($training) {
            $data = $training->getNotificationsData();
            return sprintf(
                "<li>%s</li><li>%s</li><li>%s</li>",
                __('notifications.email_verified.merged.details.first_day', [
                    'date' => $data['first_date'],
                    'time' => $data['first_date_start_and_end_time'],
                ]),
                __('notifications.email_verified.merged.details.second_day', [
                    'date' => $data['second_date'],
                    'time' => $data['second_date_start_and_end_time'],
                ]),
                __('notifications.email_verified.merged.details.location', [
                    'location' => $data['location'],
                ])
            );
        })->toArray()) . '</ul>';
    }

    private function formatTrainingProposals($kitas): string
    {
        $proposals = $kitas->flatMap(function ($kita) {
            return $kita->trainingProposals->map(function ($proposal, $index) {
                return __(
                    $index > 0
                        ? 'notifications.email_verified.default.proposals.other'
                        : 'notifications.email_verified.default.proposals.first',
                    [
                        'first_date'  => $proposal->first_date->format('d.m.Y'),
                        'second_date' => $proposal->second_date->format('d.m.Y'),
                    ]
                );
            });
        });

        if ($proposals->isEmpty()) {
            return __('notifications.email_verified.default.no_proposals');
        }

        return '<ul>' . implode('', $proposals->map(fn($item) => "<li>{$item}</li>")->toArray()) . '</ul>';
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
