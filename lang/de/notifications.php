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
            'error'  => "Hoppla!",
            'common' => "Hallo!",
        ],
        'salutation'      => "Mit freundlichen und gesunden Grüßen, \nDein :from",
        'salutation_from' => sprintf('%s Team', config('app.name')),
        'subcopy'         => "Wenn Sie Schwierigkeiten beim Klicken auf die Schaltfläche \":actionText\" haben, kopieren Sie die folgende URL und fügen Sie sie in Ihren Webbrowser ein: \n",
        'copyright'       => "&#169; " . date("Y") . " " . config('app.name'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Password Notification Lines
    |--------------------------------------------------------------------------
    */

    'reset_password' => [
        'subject'     => "Passwortwiederherstellung",
        'greeting'    => "Hallo!",
        'action_text' => "Passwort wiederherstellen",
        'first_line'  => "Dies ist eine E-Mail zur Passwortwiederherstellung. Wenn Sie sich nicht angemeldet haben und diese Benachrichtigung erhalten haben, teilen Sie uns dies bitte mit.",
        'second_line' => "Um Ihr Passwort wiederherzustellen, drücken Sie bitte die Schaltfläche:",
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verification' => [
        'subject'     => "E-Mail-Bestätigung",
        'greeting'    => "Hallo!",
        'action_text' => "Bestätigen",
        'first_line'  => "Bestätigen Sie Ihre E-Mail-Adresse, um die Registrierung Ihres Kontos abzuschließen. Wenn Sie kein Konto in unserem System erstellt haben, ignorieren Sie bitte diese Nachricht.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Welcome Notification Lines
    |--------------------------------------------------------------------------
    */

    'welcome' => [
        'subject'     => "Konto erstellt",
        'greeting'    => "Hallo :name,",
        'action_text' => "Neues Passwort festlegen",
        'first_line'  => "du wurdest zum BeoKiz Portal der Berliner Meilensteine eingeladen.",
        'second_line' => "Damit du dich anmelden kannst, musst du erstmal ein Passwort setzen:",
    ],

];
