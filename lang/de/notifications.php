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
        'subcopy'         => "Wenn Sie Schwierigkeiten beim Klicken auf die Schaltfläche \":actionText\" haben, kopieren Sie die folgende URL und fügen Sie sie in Ihren Webbrowser ein:  \n",
        'copyright'       => "&#169; " . date("Y") . " " . config('app.name'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Password Notification Lines
    |--------------------------------------------------------------------------
    */

    'reset_password' => [
        'subject'     => sprintf("%s: Passwort-Wiederherstellung", config('app.name')),
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
        'subject'     => sprintf("%s: Bitte bestätigen Sie Ihre E-Mail-Adresse", config('app.name')),
        'greeting'    => "Sehr geehrte Damen und Herren,", // OLD: Sehr geehrte :name!
        'action_text' => "Bestätigen",
        'first_line'  => "Vielen Dank für Ihr Interesse an der BeoKiz-Schulung. Bitte klicken Sie auf folgenden Link, um Ihre Terminvorschläge für die BeoKiz-Schulung zu übermitteln. Dieser Schritt stellt sicher, dass wir Sie unter Ihrer angegebenen E-Mail-Adresse erreichen können.", // OLD: Bitte bestätigen Sie Ihre E-Mail-Adresse, indem Sie auf den folgenden Link klicken:
        'second_line' => "Zur finalen Bestätigung Ihres Schulungstermins wird sich ein BeoKiz-Multiplikator oder eine BeoKiz-Multiplikatorin mit Ihnen in Verbindung setzen.",
        'salutation'  => sprintf("Herzlichen Dank und beste Grüße,,  \nIhr %s-Team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verified Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verified' => [
        'subject'             => sprintf("%s: Bestätigung Ihrer Anmeldung zur BeoKiz-Schulung", config('app.name')),
        'greeting'            => "Sehr geehrte :name!",
        'first_line'          => "Ihre Terminanfrage(n) zur BeoKiz-Schulung: \n :training_proposals",
        'second_line'         => "Zur Terminbestätigung wird sich einer unserer BeoKiz-Multiplikator: innen bald mit Ihnen in Verbindung setzen.",
        'salutation'          => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
        'first_training_item' => "am :first_date und :second_date",
        'other_training_item' => "oder am :first_date und :second_date",
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FA Verification Notification Lines
    |--------------------------------------------------------------------------
    */

    '2fa_verification' => [
        'subject'     => sprintf("%s: 2FA Bestätigungscode", config('app.name')),
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
        'subject'     => sprintf("%s: Konto erstellt", config('app.name')),
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
        'subject'     => sprintf("%s: Passwortänderung für das BeoKiz-Ampelportal bestätigt", config('app.name')),
        'greeting'    => "Hallo :name,",
        'first_line'  => "wir möchten Sie darüber informieren, dass das Passwort für Ihr BeoKiz-Konto erfolgreich geändert wurde.",
        'second_line' => "Wenn Sie diese Änderung nicht veranlasst haben, kontaktieren Sie bitte sofort unseren Support unter <:support_email>.",
        'third_line'  => "Ihre Sicherheit ist uns wichtig. Es ist immer eine gute Idee, regelmäßig Ihr Passwort zu ändern und sicherzustellen, dass Sie ein starkes und einzigartiges Passwort für jedes Online-Konto verwenden.",
        'fourth_line' => "Vielen Dank, dass Sie BeoKiz verwenden!",
    ],

    /*
    |--------------------------------------------------------------------------
    | Connected To Kitas Notification Lines
    |--------------------------------------------------------------------------
    */

    'connected_to_kitas' => [
        'subject'     => sprintf("%s: Mit Einrichtungen verbunden", config('app.name')),
        'greeting'    => "Hallo :name,",
        'first_line'  => "sie wurden soeben im BeoKiz-Ampel-Portal einer weiteren Einrichtung zugeordnet. Sie sind nun folgenden Einrichtungen zugeordnet:  \n :kitas",
        'second_line' => "Ihre Zugangsdaten haben sich hierdurch nicht verändert.",
        'third_line'  => "Falls Diese Zuordnung Ihrer Meinung nach ein Fehler ist, kontaktieren Sie bitte unseren Support unter <:support_email>",
    ],

    /*
    |--------------------------------------------------------------------------
    | Yearly Evaluation Reminder Notification Lines
    |--------------------------------------------------------------------------
    */

    'yearly_evaluation_reminder'             => [
        'subject'     => sprintf("%s: Jährliche Evaluierungserinnerung", config('app.name')),
        'greeting'    => "Sehr geehrte Einrichtungsleitung,",
        'first_line'  => "die jährliche Rückmeldung zur Statistischen Auswertung des Sprachstandsfeststellung für Kinder in Kindertageseinrichtungen und Kindertagespflege (Statuserhebung) für das Kita-Jahr :evaluation_year  muss bis zum :survey_end_date erfolgen.",
        'second_line' => "Bisher haben wir aus Ihrer Einrichtung noch keine Statuserhebung erhalten. Daher bitten wir Sie mit dieser Mail darum diese bis zum :survey_end_date einzureichen. Die entsprechende Funktion ist in ihrem Nutzer*innenkonto auf <a href=':site'>:site</a> verfügbar.",
        'third_line'  => "Vielen Dank und beste Grüße",
        'salutation'  => sprintf('Ihr %s-Team', config('app.name')) . " \nim Auftrag der Senatsverwaltung für Bildung, Jugend und Familie",
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Notification Lines
    |--------------------------------------------------------------------------
    */

    // 'confirmed' status notification
    'training_confirmed'                     => [
        'subject'     => sprintf("%s: Bestätigung des Schulungstermine am :first_date und :second_date", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der Kita,",
        'first_line'  => "Wir freuen uns, Ihnen mitteilen zu können, dass Ihr gewählter Schulungstermin bestätigt werden konnte.",
        'second_line' => "1. Schulungstag: :first_date von :first_date_start_and_end_time  \n2. Schulungstag: :second_date von :second_date_start_and_end_time  \nOrt: :location  \nIhr Multiplikator: :multiplier_name ",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    // 'completed' status notification
    'training_completed'                     => [
        'subject'     => sprintf("%s: Schulung erfolgreich abgeschlossen", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der Kita,",
        'first_line'  => "Wir freuen uns, Ihnen mitzuteilen, dass Ihre Schulung am :first_date und :second_date erfolgreich abgeschlossen wurde.",
        'second_line' => sprintf("Sie werden in Kürze ihren Zugang zum %s Portal erhalten.", config('app.name')),
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    // 'cancelled' status notification
    'training_cancelled'                     => [
        'subject'     => sprintf("%s: Absage des Schulungstermine am :first_date und :second_date", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der Kita,",
        'first_line'  => "Leider müssen wir Ihnen mitteilen, dass der Schulungstermin am :first_date und :second_date abgesagt werden musste.",
        'second_line' => "Wir entschuldigen uns für etwaige Unannehmlichkeiten.",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Proposal Notification Lines
    |--------------------------------------------------------------------------
    */

    // 'confirmation_pending' status notification
    'training_proposal_confirmation_pending' => [
        'subject'     => sprintf("%s: Bestätigung des Terminvorschlags am :first_date und :second_date", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der Kita, ",
        'first_line'  => "Bitte bestätigen Sie den vorgeschlagenen Schulungstermin durch Klicken auf den folgenden Link: :confirmation_link",
        'second_line' => "Schulungstage: :first_date und :second_date jeweils von 09 bis 17 Uhr  \nOrt: :location  <br/> Ihr Multiplikator: :multiplier_name ",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
        'copy_label'  => 'Kopie',
    ],

];
