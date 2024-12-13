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
        'subject'     => ':app_name: Bitte bestätigen Sie Ihre E-Mail-Adresse',
        'greeting'    => 'Sehr geehrte Damen und Herren,',
        'action_text' => 'Bestätigen',
        'first_line'  => [
            'default' => 'Vielen Dank für Ihr Interesse an der BeoKiz-Schulung. Bitte klicken Sie auf folgenden Link, um Ihre Terminvorschläge für die BeoKiz-Schulung zu übermitteln. Dieser Schritt stellt sicher, dass wir Sie unter Ihrer angegebenen E-Mail-Adresse erreichen können.',
            'self_training' => 'Vielen Dank für Ihr Interesse an der BeoKiz-Schulung. Bitte klicken Sie auf folgenden Link, um Ihre E-Mail-Adresse zu bestätigen. Dieser Schritt stellt sicher, dass wir Sie unter Ihrer angegebenen E-Mail-Adresse erreichen können.',
        ],
        'second_line' => [
            'default' => 'Ein BeoKiz-Multiplikator oder eine BeoKiz-Multiplikatorin wird sich mit Ihnen in Verbindung setzen, um Ihren Schulungstermin zu bestätigen.',
            'self_training' => 'Ein zuständiger BeoKiz-Multiplikator oder eine zuständige BeoKiz-Multiplikatorin wird sich mit Ihnen in Verbindung setzen, um einen Schulungstermin zu vereinbaren.',
            'merged_training' => 'Nach Bestätigung ihrer Email sollten, werden Sie direkt eine Terminbestätigung erhalten.',
        ],
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Träger Mutliplikator new selftraining Kita Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_new_selftraining_kita' => [
        'subject'     => sprintf("%s: Neue Schulungsanfrage: Bitte einen Schulungstermin mit :kita_name vereinbaren", config('app.name')),
        'greeting'    => "Hallo :multi_name,",
        'first_line'  => "Soeben hat sich die Einrichtung :kita_name über das BeoKiz-Portal registriert und den Wunsch nach einer Schulung geäußert. Die Kita wurde bereits darüber informiert, dass Du dich mit ihr in Verbindung setzen wirst.",
        'second_line' => "Bitte kontaktiere die Kita, um mit ihr einen Schulungstermin zu vereinbaren:",
        'details'     => "
                            - **Kitanummer:** :kita_number
                            - **Einrichtungsname:** :kita_name
                            - **Anschrift:** :kita_address
                            - **Ansprechpartner:in:** :manager_name
                            - **E-Mail:** :manager_email
                            - **Telefon:** :manager_phone
                            - **Anmerkungen der Kita:** :kita_remarks",
        'third_line'  => "Bitte vergiss nicht, die Schulung nach Vereinbarung im BeoKiz-Portal zu hinterlegen.",
        'closing_line'=> "Vielen Dank für Deine Unterstützung und Deine sorgfältige Bearbeitung dieser Anfrage.",
        'salutation'  => sprintf("Danke und beste Grüße,  \das %s-Team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Verified Notification Lines
    |--------------------------------------------------------------------------
    */

    'email_verified' => [
        'common' => [
            'greeting'  => "Sehr geehrte:r :name!",
            'salutation' => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
        ],
        'merged' => [
            'subject'         => sprintf("%s: Bestätigung Ihrer BeoKiz-Schulung", config('app.name')),
            'confirmation'    => "Ihre Schulung ist hiermit bestätigt.",
            'details_header'  => "Hier sind die Details: ",
            'closing'         => "Wir freuen uns auf Sie und Ihr Team.",
            'no_details'      => "Noch keine Details verfügbar.",
            'details' => [
                'first_day'   => "Erster Schulungstag: :date (:time)",
                'second_day'  => "Zweiter Schulungstag: :date (:time)",
                'location'    => "Ort: :location",
            ],
        ],
        'default' => [
            'subject'         => sprintf("%s: Bestätigung Ihrer Terminvorschläge zur BeoKiz-Schulung", config('app.name')),
            'first_line'      => "Ihre Terminanfrage(n) zur BeoKiz-Schulung: \n :training_proposals <br/> sind eingegangen.",
            'second_line'     => "Zur Terminbestätigung wird sich einer unserer BeoKiz-Multiplikator:innen bald mit Ihnen in Verbindung setzen.",
            'proposals' => [
                'first' => "am :first_date und :second_date",
                'other' => "oder am :first_date und :second_date",
            ],
            'no_proposals'    => "Noch keine Terminvorschläge verfügbar.",
        ],
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
        'first_line'  => "du wurdest zum BeoKiz-Portal eingeladen.",
        'second_line' => "Damit du dich anmelden kannst, musst du erstmal ein Passwort setzen:",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nDein %s-Team", config('app.name')),
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

    'new_operator_kita' => [
        'subject'     => sprintf("%s: Schulungswunsch der KiTa :kita_name", config('app.name')),
        'greeting'    => "Hallo liebe Multiplikator*innen vom Träger :operator_name,",
        'first_line'  => "im BeoKiz-Anmeldeportal hat sich die KiTa :kita_name für eine BeoKiz Schulung anmelden wollen.",
        'second_line' => "Die KiTa hat vom System automatisch eine Mail erhalten, in der sie darauf hingewiesen wurde, dass Ihr Euch – in Eurer Funktion als Träger-Multis - bei der KiTa melden werden, um weitere Schritte abzustimmen.",
        'third_line'  => "Wenn ihr einen Schulungstermin mit der KiTa abgesprochen habt, legt diesen bitte selbst im Portal an.",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Yearly Evaluation Reminder Notification Lines
    |--------------------------------------------------------------------------
    */

    'yearly_evaluation_reminder' => [
        'subject'     => sprintf("%s: Jährliche Evaluierungserinnerung", config('app.name')),
        'greeting'    => "Sehr geehrte:r Einrichtungsleitung,",
        'first_line'  => "die jährliche Rückmeldung zur Statistischen Auswertung des Sprachstandsfeststellung für Kinder in Kindertageseinrichtungen und Kindertagespflege (Statuserhebung) für das KiTa-Jahr :evaluation_year  muss bis zum :survey_end_date erfolgen.",
        'second_line' => "Bisher haben wir aus Ihrer Einrichtung noch keine Statuserhebung erhalten. Daher bitten wir Sie mit dieser Mail darum diese bis zum :survey_end_date einzureichen. Die entsprechende Funktion ist in ihrem Nutzer*innenkonto auf <a href=':site'>:site</a> verfügbar.",
        'third_line'  => "Vielen Dank und beste Grüße",
        'salutation'  => sprintf('Ihr %s-Team', config('app.name')) . " \nim Auftrag der Senatsverwaltung für Bildung, Jugend und Familie",
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Backup Notification Lines
    |--------------------------------------------------------------------------
    */

    'database_backup'                        => [
        'subject'    => sprintf("%s: Tägliches Datenbank-Backup", config('app.name')),
        'greeting'   => "Hallo,",
        'first_line' => "ein tägliches Backup der Datenbank befindet sich in den angehängten Dateien.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Notification Lines
    |--------------------------------------------------------------------------
    */

    // 'confirmed' status notification
    'training_confirmed'                     => [
        'subject'     => sprintf("%s: Bestätigung des Schulungstermine am :first_date und :second_date", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der KiTa,",
        'first_line'  => "Wir freuen uns, Ihnen mitteilen zu können, dass Ihr gewählter Schulungstermin bestätigt werden konnte.",
        'second_line' => "1. Schulungstag: :first_date von :first_date_start_and_end_time  \n2. Schulungstag: :second_date von :second_date_start_and_end_time  \nOrt: :location  \nIhr Multiplikator: :multiplier_name ",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    // 'completed' status notification
    'training_completed'                     => [
        'subject'     => sprintf("%s: Schulung erfolgreich abgeschlossen", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der KiTa,",
        'first_line'  => "Wir freuen uns, Ihnen mitzuteilen, dass Ihre Schulung am :first_date und :second_date erfolgreich abgeschlossen wurde.",
        'second_line' => sprintf("Sie werden in Kürze Ihren Zugang zum %s Portal, Ihre Teilnahmebescheinigung sowie den Link zu einem Feedbackfragebogen erhalten.", config('app.name')),
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
    ],

    // 'cancelled' status notification
    'training_cancelled'                     => [
        'subject'     => sprintf("%s: Absage des Schulungstermine am :first_date und :second_date", config('app.name')),
        'greeting'    => "Sehr geehrtes Pädagogisches Team der KiTa,",
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
        'greeting'    => "Sehr geehrtes Pädagogisches Team der KiTa, ",
        'first_line'  => "Bitte bestätigen Sie den vorgeschlagenen Schulungstermin durch Klicken auf den folgenden Link: :confirmation_link",
        'second_line' => "Schulungstage: :first_date und :second_date jeweils von 09 bis 17 Uhr  \nOrt: :location  <br/> Ihr Multiplikator: :multiplier_name ",
        'salutation'  => sprintf("Mit freundlichen Grüßen,  \nIhr %s-Team", config('app.name')),
        'copy_label'  => 'Kopie',
    ],

    /*
    |--------------------------------------------------------------------------
    | Kita Certificate Notification Lines
    |--------------------------------------------------------------------------
    */

    'kita_certificate' => [
        'subject'     => sprintf("%s: BeoKiz-Schulung abgeschlossen", config('app.name')),
        'greeting'    => "Hallo BeoKiz :full_name,",
        'first_line'  => "Es gibt viel zu entdecken - zwar ist die Mail etwas länger, aber es lohnt sich :-)",
        'second_line' => "Sie haben die Schulung zum BeoKiz-Verfahren erfolgreich abgeschlossen. Herzlichen Glückwunsch! Im Anhang dieser Mail erhalten Sie die Teilnahmebescheinigung für Ihre Einrichtung.",
        'third_line'  => "Darüber hinaus haben wir noch eine Bitte an Sie: Teil der Einführung des BeoKiz-Verfahrens ist eine wissenschaftliche Begleitung, durch die stetig Faktoren identifiziert und angepasst werden, die die Einführung von BeoKiz im Land beeinflussen. Dies hat zum Ziel, die Einführung von BeoKiz so zu gestalten, dass sie auf die Bedürfnisse aller Beteiligten im Prozess kontinuierlich angepasst wird. Von einer wirkungsvollen und flächendeckenden Einführung des Verfahrens profitieren dann vor allem auch die Kinder.",
        'fourth_line' => "Daher bitten wir Sie und Ihre Mitarbeitenden, einen ersten Online-Fragebogen zur BeoKiz-Einführung auszufüllen. Alle Angaben sind vollständig anonym. Ein Rückschluss auf Sie oder Ihre Einrichtung ist nicht möglich. Den Link zum Fragebogen sowie eine Einladung zum Ausdrucken mit QR-Code für bequemes Ausfüllen am Smartphone finden Sie unter: http://www.kitearo.de/BeoKiz-Fragebogen/",
        'fifth_line'  => "Des Weiteren erinnern wir Sie an dieser Stelle gerne an das BeoKiz-Forum. Das BeoKiz-Forum findet jeden letzten Mittwoch im Monat statt, mit Ausnahme im Dezember - da ist es der letzte Mittwoch vor Weihnachten.",
        'sixth_line'  => "Dieses Forum ist für alle bereits geschulten Fachkräfte und Leitungen aus Berliner KiTas oder der Kindertagespflege. Ein/e Kolleg:in aus dem KiTeAro-Institut wird moderierend und für Fragen anwesend sein. Wir möchten mit diesem Format insbesondere den kollegialen Austausch der Teilnehmenden untereinander unterstützen.",
        'seventh_line' => "Eine Anmeldung ist nicht notwendig.",
        'zoom_title' => '**Zoom-Link zum BeoKiz-Forum für geschulte KiTas:**',
        'zoom_link' => 'https://link.kitearo.de/BeoKizForum',
        'zoom_meeting_id' => 'Meeting-ID: 867 1267 9075',
        'zoom_password' => 'Kennwort: 236794',
        'salutation'  => sprintf("Vielen Dank und beste Grüße,  \nIhr %s Team", config('app.name')),
    ],

];
