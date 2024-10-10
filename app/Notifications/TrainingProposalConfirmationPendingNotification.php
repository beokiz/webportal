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
 * Training Proposal Confirmation Pending Notification
 *
 * @package \App\Notifications
 */
class TrainingProposalConfirmationPendingNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    public $args;

    /**
     * Create a new notification instance.
     *
     * @param array $args
     * @return void
     */
    public function __construct(array $args)
    {
        $this->args = $args;
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
        return (new CustomMailMessage)
            ->subject(__('notifications.training_proposal_confirmation_pending.subject', [
                'first_date'  => $this->args['first_date'],
                'second_date' => $this->args['second_date'],
            ]))
            ->greeting(__('notifications.training_proposal_confirmation_pending.greeting'))
            ->line(__('notifications.training_proposal_confirmation_pending.first_line', [
                'confirmation_link' => $this->args['confirmation_link'],
            ]))
            ->line(__('notifications.training_proposal_confirmation_pending.second_line', [
                'first_date'      => $this->args['first_date'],
                'second_date'     => $this->args['second_date'],
                'location'        => $this->args['location'],
                'multiplier_name' => $this->args['multiplier_name'],
            ]))
            ->salutation(__('notifications.training_proposal_confirmation_pending.salutation'));
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
