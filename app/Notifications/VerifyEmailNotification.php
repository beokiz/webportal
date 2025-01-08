<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Messages\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

/**
 * Verify Email Notification
 *
 * @package \App\Notifications
 */
class VerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    public $notifiable;

    /**
     * The callback that should be used to create the verify email URL.
     *
     * @var \Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

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
        $verificationUrl = $this->verificationUrl($notifiable);

        $this->notifiable = $notifiable;

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return $this->buildMailMessage($verificationUrl);
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     * @return CustomMailMessage
     */
    protected function buildMailMessage($url)
    {
        // Ermitteln der ersten Kita des Benutzers
        $kita = $this->notifiable->kitas->first();

        // Prüfen, ob es sich um eine zusammengelegte Schulung handelt
        $isMergedTraining = $kita && $kita->num_pedagogical_staff <= 10;
        $isSelfTraining = $kita && !empty($kita->operator_id);

        // Übersetzungsschlüssel basierend auf den Bedingungen auswählen
        $firstLineKey = $isSelfTraining
            ? 'notifications.email_verification.first_line.self_training'
            : 'notifications.email_verification.first_line.default';

        if ($isSelfTraining) {
            $secondLineKey = 'notifications.email_verification.second_line.self_training';
        } elseif ($isMergedTraining) {
            $secondLineKey = 'notifications.email_verification.second_line.merged_training';
        } else {
            $secondLineKey = 'notifications.email_verification.second_line.default';
        }

        // E-Mail erstellen
        $mailMessage = (new CustomMailMessage)
            ->subject(__('notifications.email_verification.subject', [
                'app_name' => config('app.name'),
            ]))
            ->greeting(__('notifications.email_verification.greeting'))
            ->line(__('' . $firstLineKey));

        // Zweite Zeile nur hinzufügen, wenn sie existiert und nicht null ist
        if ($secondLineKey && __($secondLineKey) !== $secondLineKey) {
            $mailMessage->line(__('' . $secondLineKey));
        }

        // Aktion hinzufügen
        $mailMessage->action(__('notifications.email_verification.action_text'), $url);

        // E-Mail abschließen
        return $mailMessage->salutation(__('notifications.email_verification.salutation', [
            'app_name' => config('app.name'),
        ]));
    }
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Set a callback that should be used when creating the email verification URL.
     *
     * @param \Closure $callback
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param \Closure $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
