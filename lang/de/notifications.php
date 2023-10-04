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
        'salutation'      => "Mit freundlichen und gesunden Grüßen, \nIhr :from",
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
        'subject'     => "BeoKiz Passwort-Wiederherstellung",
        'greeting'    => "Hallo :name,",
        'action_text' => "Passwort wiederherstellen",
        'first_line'  => "du hast eine Anfrage zur Zurücksetzung deines Passworts für dein Konto des BeoKiz Ampel-Portals der Berliner Meilensteine erhalten.",
        'second_line' => "Um dein Passwort zurückzusetzen, folge bitte dem untenstehenden Link:",
        'third_line'  => "Falls du diese Anfrage nicht gestellt hast, ignoriere diese E-Mail bitte und informiere uns eventuell über <:support_email>, damit wir entsprechende Maßnahmen ergreifen können.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verification' => [
        'subject'     => "BeoKiz-E-Mail-Bestätigung",
        'greeting'    => "Hallo!",
        'action_text' => "Bestätigen",
        'first_line'  => "Bestätigen Sie Ihre E-Mail-Adresse, um die Registrierung Ihres Kontos abzuschließen. Wenn Sie kein Konto in unserem System erstellt haben, ignorieren Sie bitte diese Nachricht.",
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FA Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    '2fa_verification' => [
        'subject'     => "2FA BeoKiz-Bestätigungscode",
        'action_text' => "Überprüfen Sie den 2FA (Zwei-Faktor-Authentifizierung) Code",
        'first_line'  => "Wenn Sie nicht versucht haben, sich anzumelden und diese Benachrichtigung erhalten haben, ignorieren Sie diese Nachricht bitte.",
        'second_line' => "Code für die Zwei-Faktor-Authentifizierung: :code",
    ],

    /*
    |--------------------------------------------------------------------------
    | Welcome Notification Lines
    |--------------------------------------------------------------------------
    */

    'welcome' => [
        'subject'     => "BeoKiz-Konto erstellt",
        'greeting'    => "Hallo :name,",
        'action_text' => "Neues Passwort festlegen",
        'first_line'  => "du wurdest zum BeoKiz Ampel-Portal der Berliner Meilensteine eingeladen.",
        'second_line' => "Damit du dich anmelden kannst, musst du erstmal ein Passwort setzen:",
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Changed Notification Lines
    |--------------------------------------------------------------------------
    */

    'password_changed' => [
        'subject'     => "Passwortänderung für das BeoKiz-Ampelportal bestätigt",
        'greeting'    => "Hallo :name,",
//        'action_text' => "",
        'first_line'  => "wir möchten Sie darüber informieren, dass das Passwort für Ihr BeoKiz-Konto erfolgreich geändert wurde.",
        'second_line' => "Wenn Sie diese Änderung nicht veranlasst haben, kontaktieren Sie bitte sofort unseren Support unter <:support_email>.",
        'third_line'  => "Ihre Sicherheit ist uns wichtig. Es ist immer eine gute Idee, regelmäßig Ihr Passwort zu ändern und sicherzustellen, dass Sie ein starkes und einzigartiges Passwort für jedes Online-Konto verwenden.",
        'fourth_line' => "Vielen Dank, dass Sie BeoKiz verwenden!",
    ],

];
