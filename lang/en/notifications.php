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
        'salutation'      => "Regards, \n:from",
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
        'subject'     => "Password Recovery",
        'greeting'    => "Hello!",
        'action_text' => "Restore Password",
        'first_line'  => "This is a password recovery email. If you have not tried to log in and you have received this notification, please let us know.",
        'second_line' => "To restore your password, please press the button:",
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verification' => [
        'subject'     => "Email Verification",
        'greeting'    => "Hello!",
        'action_text' => "Confirm",
        'first_line'  => "Confirm your email address to complete account registration. If you have not created an account in our system, please ignore this message.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Welcome Notification Lines
    |--------------------------------------------------------------------------
    */

    'welcome' => [
        'subject'     => "Created Account",
        'greeting'    => "Congratulations to you!",
        'first_line'  => "You have successfully created an account!",
        'second_line' => "If you have any questions or problems using our system, please email us: <:support_email>",
    ],

];
