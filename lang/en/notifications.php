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
        'subject'     => "BeoKiz password recovery",
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
        'subject'     => "BeoKiz email verification",
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
        'subject'     => "2FA BeoKiz verification code",
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
        'subject'     => "Created BeoKiz account",
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
        'subject'     => "BeoKiz password change confirmed",
        'greeting'    => "Hello :name,",
//        'action_text' => "",
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
        'subject'     => "Connected to Kitas",
        'greeting'    => "Hello :name,",
        'first_line'  => "you have just been assigned to another facility in the BeoKiz traffic light portal. You are now assigned to the following facilities: \n :kitas",
        'second_line' => "Your access data has not changed as a result.",
        'third_line'  => "If you believe this assignment is an error, please contact our support at <:support_email>",
    ],

];
