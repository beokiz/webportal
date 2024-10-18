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
        'subject'     => sprintf("%s: Please confirm your email address", config('app.name')),
        'greeting'    => "Dear Sir or Madam,",
        'action_text' => "Confirm",
        'first_line'  => "Thank you for your interest in the BeoKiz training. Please click on the following link to submit your appointment suggestions for the BeoKiz training. This step ensures that we can reach you at your provided email address.",
        'second_line' => "A BeoKiz trainer will contact you to finalize the confirmation of your training appointment.",
        'salutation'  => sprintf("Thank you very much and best regards,,  \nYour %s team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verified Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verified' => [
        'subject'             => sprintf("%s: Confirmation of your suggested dates for BeoKiz training", config('app.name')),
        'greeting'            => "Dear :name!",
        'first_line'          => "Your training request(s) for BeoKiz: \n :trainings_list <br/> have been received.",
        'second_line'         => "One of our BeoKiz multipliers will soon contact you to confirm the appointment.",
        'salutation'          => sprintf("Best regards,  \n your %s team", config('app.name')),
        'first_training_item' => "on :first_date and :second_date",
        'other_training_item' => "or on :first_date and :second_date",
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
        'first_line'  => "you have been invited to the BeoKiz portal.",
        'second_line' => "To be able to log in, you must first set a password:",
        'salutation'  => sprintf("Best regards,  \n your %s team", config('app.name')),
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
        'subject'     => sprintf("%s: Connected to KiTas", config('app.name')),
        'greeting'    => "Hello :name,",
        'first_line'  => "you have just been assigned to another facility in the BeoKiz traffic light portal. You are now assigned to the following facilities: \n :kitas",
        'second_line' => "Your access data has not changed as a result.",
        'third_line'  => "If you believe this assignment is an error, please contact our support at <:support_email>",
    ],

    'new_operator_kita' => [
        'subject'     => sprintf("%s: Training request from the daycare center :kita_name", config('app.name')),
        'greeting'    => "Hello dear multipliers from the provider :operator_name,",
        'first_line'  => "in the BeoKiz registration portal, the :kita_name daycare center wanted to register for a BeoKiz training course.",
        'second_line' => "The daycare center automatically received an email from the system informing them that you - in your role as provider multipliers - will contact the daycare center to coordinate further steps.",
        'third_line'  => "If you have agreed on a training date with the daycare center, please create it yourself in the portal.",
        'salutation'  => sprintf("Best regards,  \n your %s team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Yearly Evaluation Reminder Notification Lines
    |--------------------------------------------------------------------------
    */

    'yearly_evaluation_reminder' => [
        'subject'     => sprintf("%s: Yearly evaluation reminder", config('app.name')),
        'greeting'    => "Dear facility manager,",
        'first_line'  => "The annual feedback on the statistical evaluation of the language proficiency assessment for children in daycare centers and childcare (status survey) for the daycare year :evaluation_year must be provided by :survey_end_date.",
        'second_line' => "We have not yet received a status report from your facility. We therefore ask you to submit this email by :survey_end_date. The corresponding function is available in your user account on <a href=':site'>:site</a>.",
        'third_line'  => "Thanks and best regards",
        'salutation'  => sprintf('Your %s Team', config('app.name')) . "\non behalf of the Senate Department for Education, Youth and Family",
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Backup Notification Lines
    |--------------------------------------------------------------------------
    */

    'database_backup'                        => [
        'subject'    => sprintf("%s: Daily database backup", config('app.name')),
        'greeting'   => "Hello,",
        'first_line' => "a daily backup of the database is in the attached files.",
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
        'copy_label'  => 'Copy',
    ],

    /*
    |--------------------------------------------------------------------------
    | Kita Certificate Notification Lines
    |--------------------------------------------------------------------------
    */

    'kita_certificate' => [
        'subject'     => sprintf("%s: BeoKiz training completed", config('app.name')),
        'greeting'    => "Hello :full_name,",
        'first_line'  => "You have successfully completed the training on the BeoKiz process. Congratulations! Attached to this email you will find the certificate of participation for your institution.",
        'second_line' => "We also have a request for you: Part of the introduction of the BeoKiz process is scientific support, through which factors that influence the introduction of BeoKiz in the country are constantly identified and adjusted. The aim of this is to design the introduction of BeoKiz in such a way that it is continuously adapted to the needs of everyone involved in the process. The children in particular will benefit from an effective and comprehensive introduction of the process.",
        'third_line'  => "We therefore ask you and your employees to fill out an initial online questionnaire on the introduction of BeoKiz. All information is completely anonymous. It is not possible to draw conclusions about you or your institution. The link to the questionnaire, as well as an invitation to print out with a QR code for easy completion on your smartphone, can be found at: http://www.kitearo.de/BeoKiz-Fragebogen/",
        'salutation'  => sprintf("Many thanks and best regards,  \n your %s Team", config('app.name')),
    ],

];
