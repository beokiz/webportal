<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Common Lines
    |--------------------------------------------------------------------------
    */

    'common' => [
        'greetings'       => [
            'error'  => "Whoops!",
            'common' => "Hello!",
        ],
        'salutation'      => "Regards, \nyour :from",
        'salutation_from' => sprintf('%s Team', config('app.name')),
        'subcopy'         => "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below into your web browser: \n",
        'copyright'       => "&#169; " . date("Y") . " " . config('app.name'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Password Notification Lines
    |--------------------------------------------------------------------------
    */

    'reset_password' => [
        'subject'     => sprintf("%s: Password recovery", config('app.name')),
        'greeting'    => "Hello :name,",
        'action_text' => "Restore Password",
        'first_line'  => "We have received a request to reset your password for your account on the BeoKiz traffic light portal of the Berlin Milestones.",
        'second_line' => "To reset your password, please follow the link below:",
        'third_line'  => "If you did not make this request, please ignore this email and possibly inform us at <:support_email> so that we can take appropriate action.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verification' => [
        'subject'     => sprintf("%s: Email verification", config('app.name')),
        'greeting'    => "Hello!",
        'action_text' => "Confirm",
        'first_line'  => "Confirm your email address to complete account registration. If you have not created an account in our system, please ignore this message.",
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FA Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    '2fa_verification' => [
        'subject'     => sprintf("%s: 2FA verification code", config('app.name')),
        'action_text' => "Verify 2FA Code",
        'first_line'  => "If you have not tried to log in and have received this notification, please ignore this message.",
        'second_line' => "Code for two-factor authentication: :code",
    ],

    /*
    |--------------------------------------------------------------------------
    | Welcome Notification Lines
    |--------------------------------------------------------------------------
    */

    'welcome' => [
        'subject'     => sprintf("%s: Created account", config('app.name')),
        'greeting'    => "Hello :name,",
        'action_text' => "Set new password",
        'first_line'  => "you have been invited to the BeoKiz portal of Berlin milestones.",
        'second_line' => "To be able to log in, you must first set a password:",
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Changed Notification Lines
    |--------------------------------------------------------------------------
    */

    'password_changed' => [
        'subject'     => sprintf("%s: Password change confirmed", config('app.name')),
        'greeting'    => "Hello :name,",
        'first_line'  => "we would like to inform you that the password for your Beokiz account has been successfully changed.",
        'second_line' => "If you did not initiate this change, please contact our support immediately at <:support_email>.",
        'third_line'  => "Your safety is important to us. It's always a good idea to change your password regularly and ensure you use a strong and unique password for each online account.",
        'fourth_line' => "Thank you for using Beokiz!",
    ],

    /*
    |--------------------------------------------------------------------------
    | Connected To Kitas Notification Lines
    |--------------------------------------------------------------------------
    */

    'connected_to_kitas' => [
        'subject'     => sprintf("%s: Connected to Kitas", config('app.name')),
        'greeting'    => "Hello :name,",
        'first_line'  => "you have just been assigned to another facility in the BeoKiz traffic light portal. You are now assigned to the following facilities: \n :kitas",
        'second_line' => "Your access data has not changed as a result.",
        'third_line'  => "If you believe this assignment is an error, please contact our support at <:support_email>",
    ],

    /*
    |--------------------------------------------------------------------------
    | Yearly Evaluation Reminder Notification Lines
    |--------------------------------------------------------------------------
    */

    'yearly_evaluation_reminder'             => [
        'subject'     => sprintf("%s: Yearly evaluation reminder", config('app.name')),
        'greeting'    => "Dear facility manager,",
        'first_line'  => "The annual feedback on the statistical evaluation of the language proficiency assessment for children in daycare centers and childcare (status survey) for the daycare year :evaluation_year must be provided by :survey_end_date.",
        'second_line' => "We have not yet received a status report from your facility. We therefore ask you to submit this email by :survey_end_date. The corresponding function is available in your user account on <a href=':site'>:site</a>.",
        'third_line'  => "Thanks and best regards",
        'salutation'  => sprintf('Your %s Team', config('app.name')) . "\non behalf of the Senate Department for Education, Youth and Family",
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Notification Lines
    |--------------------------------------------------------------------------
    */

    // 'confirmed' status notification
    'training_confirmed'                     => [
        'subject'     => sprintf("%s: Confirmation of training dates on :first_date and :second_date", config('app.name')),
        'greeting'    => "Dear educational team of the daycare center,",
        'first_line'  => "We are pleased to inform you that your chosen training date has been confirmed.",
        'second_line' => "1st training day: :first_date from :first_date_start_and_end_time  \n2nd training day: :second_date from :second_date_start_and_end_time  \nLocation: :location  \nYour facilitator: :multiplier_name",
        'salutation'  => sprintf("Best regards,  \n your %s team", config('app.name')),
    ],

    // 'completed' status notification
    'training_completed'                     => [
        'subject'     => sprintf("%s: Training successfully completed", config('app.name')),
        'greeting'    => "Dear educational team of the daycare center,",
        'first_line'  => "We are pleased to inform you that your training on :first_date and :second_date has been successfully completed.",
        'second_line' => sprintf("You will soon receive your access to the %s portal.", config('app.name')),
        'salutation'  => sprintf("Best regards,  \n your %s team", config('app.name')),
    ],

    // 'cancelled' status notification
    'training_cancelled'                     => [
        'subject'     => sprintf("%s: Cancellation of training dates on :first_date and :second_date", config('app.name')),
        'greeting'    => "Dear educational team of the daycare center,",
        'first_line'  => "Unfortunately, we have to inform you that the training dates on :first_date and :second_date have been cancelled.",
        'second_line' => "We apologize for any inconvenience.",
        'salutation'  => sprintf("Best regards,  \n your %s team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Proposal Notification Lines
    |--------------------------------------------------------------------------
    */

    // 'confirmation_pending' status notification
    'training_proposal_confirmation_pending' => [
        'subject'     => sprintf("%s: Confirmation of the date proposal on :first_date and :second_date", config('app.name')),
        'greeting'    => "Dear educational team of the daycare center, ",
        'first_line'  => "Please confirm the proposed training date by clicking on the following link: :confirmation_link",
        'second_line' => "Training days: :first_date and :second_date from 9 a.m. to 3 p.m. \nLocation: :location \nMultiplier: :multiplier_name",
        'salutation'  => sprintf("Best regards,  \n your %s team", config('app.name')),
    ],

];
