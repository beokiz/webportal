<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'                  => "Das Feld \":attribute\" muss akzeptiert werden.",
    'accepted_if'               => "Das Feld \":attribute\" muss akzeptiert werden, wenn :other :value ist.",
    'active_url'                => "Das Feld \":attribute\" ist keine gültige URL.",
    'after'                     => "Das Feld \":attribute\" muss ein Datum nach :date sein.",
    'after_or_equal'            => "Das Feld \":attribute\" muss ein Datum nach oder gleich :date sein.",
    'alpha'                     => "Das Feld \":attribute\" darf nur Buchstaben enthalten.",
    'alpha_dash'                => "Das Feld \":attribute\" darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.",
    'alpha_num'                 => "Das Feld \":attribute\" darf nur Buchstaben und Zahlen enthalten.",
    'array'                     => "Das Feld \":attribute\" muss ein Array sein.",
    'before'                    => "Das Feld \":attribute\" muss ein Datum vor :date sein.",
    'before_or_equal'           => "Das Feld \":attribute\" muss ein Datum vor oder gleich :date sein.",
    'between'                   => [
        'numeric' => "Das Feld \":attribute\" muss zwischen :min und :max liegen.",
        'file'    => "Das Feld \":attribute\" muss zwischen :min und :max Kilobyte groß sein.",
        'string'  => "Das Feld \":attribute\" muss zwischen :min und :max Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" muss zwischen :min und :max Elemente enthalten.",
    ],
    'boolean'                   => "Das Feld \":attribute\" muss true oder false sein.",
    'confirmed'                 => "Die Bestätigung des Feldes \":attribute\" stimmt nicht überein.",
    'current_password'          => "Das Passwort ist falsch.",
    'date'                      => "Das Feld \":attribute\" ist kein gültiges Datum.",
    'date_equals'               => "Das Feld \":attribute\" muss ein Datum gleich :date sein.",
    'date_format'               => "Das Feld \":attribute\" entspricht nicht dem Format :format.",
    'declined'                  => "Das Feld \":attribute\" muss abgelehnt werden.",
    'declined_if'               => "Das Feld \":attribute\" muss abgelehnt werden, wenn :other :value ist.",
    'different'                 => "Die Felder \":attribute\" und :other müssen unterschiedlich sein.",
    'digits'                    => "Das Feld \":attribute\" muss :digits Ziffern enthalten.",
    'digits_between'            => "Das Feld \":attribute\" muss zwischen :min und :max Ziffern enthalten.",
    'dimensions'                => "Das Bild im Feld \":attribute\" hat ungültige Abmessungen.",
    'distinct'                  => "Das Feld \":attribute\" enthält einen doppelten Wert.",
    'email'                     => "Das Feld \":attribute\" muss eine gültige E-Mail-Adresse sein.",
    'ends_with'                 => "Das Feld \":attribute\" muss mit einem der folgenden Werte enden: :values.",
    'enum'                      => "Die ausgewählte \":attribute\" ist ungültig.",
    'exists'                    => "Die ausgewählte \":attribute\" ist ungültig.",
    'file'                      => "Das Feld \":attribute\" muss eine Datei sein.",
    'filled'                    => "Das Feld \":attribute\" muss einen Wert haben.",
    'gt'                        => [
        'numeric' => "Das Feld \":attribute\" muss größer als :value sein.",
        'file'    => "Das Feld \":attribute\" muss größer als :value Kilobyte sein.",
        'string'  => "Das Feld \":attribute\" muss mehr als :value Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" muss mehr als :value Elemente enthalten.",
    ],
    'gte'                       => [
        'numeric' => "Das Feld \":attribute\" muss größer oder gleich :value sein.",
        'file'    => "Das Feld \":attribute\" muss größer oder gleich :value Kilobyte sein.",
        'string'  => "Das Feld \":attribute\" muss größer oder gleich :value Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" muss :value Elemente oder mehr enthalten.",
    ],
    'image'                     => "Das Feld \":attribute\" muss ein Bild sein.",
    'in'                        => "Die ausgewählte \":attribute\" ist ungültig.",
    'in_array'                  => "Das Feld \":attribute\" existiert nicht in :other.",
    'integer'                   => "Das Feld \":attribute\" muss eine ganze Zahl sein.",
    'ip'                        => "Das Feld \":attribute\" muss eine gültige IP-Adresse sein.",
    'ipv4'                      => "Das Feld \":attribute\" muss eine gültige IPv4-Adresse sein.",
    'ipv6'                      => "Das Feld \":attribute\" muss eine gültige IPv6-Adresse sein.",
    'json'                      => "Das Feld \":attribute\" muss eine gültige JSON-Zeichenfolge sein.",
    'lt'                        => [
        'numeric' => "Das Feld \":attribute\" muss kleiner als :value sein.",
        'file'    => "Das Feld \":attribute\" muss kleiner als :value Kilobyte sein.",
        'string'  => "Das Feld \":attribute\" muss weniger als :value Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" muss weniger als :value Elemente enthalten.",
    ],
    'lte'                       => [
        'numeric' => "Das Feld \":attribute\" muss kleiner oder gleich :value sein.",
        'file'    => "Das Feld \":attribute\" muss kleiner oder gleich :value Kilobyte sein.",
        'string'  => "Das Feld \":attribute\" muss kleiner oder gleich :value Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" darf nicht mehr als :value Elemente enthalten.",
    ],
    'mac_address'               => "Das Feld \":attribute\" muss eine gültige MAC-Adresse sein.",
    'max'                       => [
        'numeric' => "Das Feld \":attribute\" darf nicht größer als :max sein.",
        'file'    => "Das Feld \":attribute\" darf nicht größer als :max Kilobyte sein.",
        'string'  => "Das Feld \":attribute\" darf nicht größer als :max Zeichen sein.",
        'array'   => "Das Feld \":attribute\" darf nicht mehr als :max Elemente enthalten.",
    ],
    'mimes'                     => "Das Feld \":attribute\" muss eine Datei des Typs sein: :values.",
    'mimetypes'                 => "Das Feld \":attribute\" muss eine Datei des Typs sein: :values.",
    'min'                       => [
        'numeric' => "Das Feld \":attribute\" muss mindestens :min sein.",
        'file'    => "Das Feld \":attribute\" muss mindestens :min Kilobyte groß sein.",
        'string'  => "Das Feld \":attribute\" muss mindestens :min Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" muss mindestens :min Elemente enthalten.",
    ],
    'multiple_of'               => "Das Feld \":attribute\" muss ein Vielfaches von :value sein.",
    'not_in'                    => "Die ausgewählte \":attribute\" ist ungültig.",
    'not_regex'                 => "Das Format des Feldes \":attribute\" ist ungültig.",
    'numeric'                   => "Das Feld \":attribute\" muss eine Zahl sein.",
    'password'                  => "Das Passwort ist falsch.",
    'present'                   => "Das Feld \":attribute\" muss vorhanden sein.",
    'prohibited'                => "Das Feld \":attribute\" ist verboten.",
    'prohibited_if'             => "Das Feld \":attribute\" ist verboten, wenn :other :value ist.",
    'prohibited_unless'         => "Das Feld \":attribute\" ist verboten, es sei denn, :other ist in :values.",
    'prohibits'                 => "Das Feld \":attribute\" verbietet :other, vorhanden zu sein.",
    'regex'                     => "Das Format des Feldes \":attribute\" ist ungültig.",
    'required'                  => "Das Feld \":attribute\" ist erforderlich.",
    'required_array_keys'       => "Das Feld \":attribute\" muss Einträge für enthalten: :values.",
    'required_if'               => "Das Feld \":attribute\" ist erforderlich, wenn :other :value ist.",
    'required_unless'           => "Das Feld \":attribute\" ist erforderlich, es sei denn, :other ist in :values.",
    'required_with'             => "Das Feld \":attribute\" ist erforderlich, wenn :values vorhanden ist.",
    'required_with_all'         => "Das Feld \":attribute\" ist erforderlich, wenn :values vorhanden sind.",
    'required_without'          => "Das Feld \":attribute\" ist erforderlich, wenn :values nicht vorhanden ist.",
    'required_without_all'      => "Das Feld \":attribute\" ist erforderlich, wenn keines von :values vorhanden ist.",
    'same'                      => "Die Felder \":attribute\" und :other müssen übereinstimmen.",
    'size'                      => [
        'numeric' => "Das Feld \":attribute\" muss :size sein.",
        'file'    => "Das Feld \":attribute\" muss :size Kilobyte groß sein.",
        'string'  => "Das Feld \":attribute\" muss :size Zeichen lang sein.",
        'array'   => "Das Feld \":attribute\" muss :size Elemente enthalten.",
    ],
    'starts_with'               => "Das Feld \":attribute\" muss mit einem der folgenden Werte beginnen: :values.",
    'string'                    => "Das Feld \":attribute\" muss eine Zeichenfolge sein.",
    'timezone'                  => "Das Feld \":attribute\" muss eine gültige Zeitzone sein.",
    'unique'                    => "Das Feld \":attribute\" wurde bereits verwendet.",
    'uploaded'                  => "Das Feld \":attribute\" konnte nicht hochgeladen werden.",
    'url'                       => "Das Feld \":attribute\" muss eine gültige URL sein.",
    'uuid'                      => "Das Feld \":attribute\" muss eine gültige UUID sein.",

    /*
     * Custom
     */
    'integer_or_float'          => "Das Feld \":attribute\" muss eine gültige ganze Zahl oder Fließkommazahl mit :before Dezimalstellen vor dem Dezimalpunkt und :after Dezimalstellen nach dem Dezimalpunkt sein.",
    'max_decimal_digits'        => "Das Feld \":attribute\" muss eine gültige ganze Zahl oder Fließkommazahl mit :max Dezimalstellen sein.",
    'phone'                     => "Das Feld \":attribute\" muss eine gültige Telefonnummer sein.",
    'timestamp'                 => "Das Feld \":attribute\" muss ein gültiger Zeitstempel sein.",
    'unix_path'                 => "Das Feld \":attribute\" muss ein gültiger Unix-Pfad sein.",
    'windows_disk'              => "Das Feld \":attribute\" muss ein gültiges Windows-Datenträgerlaufwerk sein.",
    'unix_path_or_windows_disk' => "Das Feld \":attribute\" muss ein gültiger Unix-Pfad oder ein gültiges Windows-Datenträgerlaufwerk sein.",
    'url_or_ipv4'               => "Das Feld \":attribute\" muss eine gültige URL oder IPv4-Adresse sein.",
    'fqdn'                      => "Das Feld \":attribute\" muss ein gültiger FQDN sein.",
    'fqdn_or_url'               => "Das Feld \":attribute\" muss ein gültiger FQDN oder eine gültige URL sein.",
    'fqdn_or_ipv4'              => "Das Feld \":attribute\" muss ein gültiger FQDN oder eine gültige IPv4-Adresse sein.",
    'url_or_fqdn_or_ipv4'       => "Das Feld \":attribute\" muss eine gültige URL, ein gültiger FQDN oder eine gültige IPv4-Adresse sein.",
    'port_number'               => "Das Feld \":attribute\" muss eine gültige TCP/IP-Portnummer sein.",
    'url_path'                  => "Das Feld \":attribute\" muss ein gültiger URL-Pfad sein.",
    'not_present'               => "Das Feld \":attribute\" darf nicht vorhanden sein",
    'not_present_with'          => "Das Feld \":attribute\" darf nicht vorhanden sein, wenn \":value\" vorhanden ist.",
    'file_name'                 => "Das Feld \":attribute\" muss einen Dateinamen mit einem der folgenden Typen sein: :values.",
    'not_match_old_password'    => "Das Feld \":attribute\" darf nicht mit dem alten Passwort übereinstimmen.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'throttle' => "Zu viele Versuche. Bitte versuchen Sie es in :seconds Sekunden erneut.",

        'invalid' => "Die ausgewählte \":attribute\" ist ungültig.",

        'password' => [
//            'regex' => "Das Passwort muss enthalten: mindestens einen Großbuchstaben, mindestens einen Kleinbuchstaben, mindestens eine Ziffer, 8 Zeichen lang.",
            'regex' => "Das Passwort erfüllt nicht die gestellten Mindestanforderungen.",
            'min'   => "Ihr Passwort muss mindestens :min Zeichen lang sein.",
        ],

        'email' => [
            'unique' => "Die Emailadresse ist schon einem anderen Nutzer zugeordnet.",
        ],

        'attribute-name' => [
            'rule-name' => "Benutzerdefinierte Nachricht",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'user'                   => "Benutzer",
        'users'                  => "Benutzer",
        'user_id'                => "Benutzer",
        'user_ids'               => "Benutzer",
        'role'                   => "Rolle",
        'roles'                  => "Rollen",
        'role_id'                => "Rolle",
        'role_ids'               => "Rollen",
        'domain'                 => "Domäne",
        'domains'                => "Domänen",
        'domain_id'              => "Domäne",
        'domain_ids'             => "Domänen",
        'subdomain'              => "Subdomäne",
        'subdomains'             => "Subdomänen",
        'subdomain_id'           => "Subdomäne",
        'subdomain_ids'          => "Subdomänen",
        'kita'                   => "Kita",
        'kitas'                  => "Kitas",
        'kita_id'                => "Kita",
        'kita_ids'               => "Kitas",
        'milestone'              => "Meilenstein",
        'milestones'             => "Meilensteine",
        'milestone_id'           => "Meilenstein",
        'milestone_ids'          => "Meilensteine",
        'evaluation'             => "Evaluation",
        'evaluations'            => "Evaluationen",
        'evaluation_id'          => "Evaluation",
        'evaluation_ids'         => "Evaluationen",
        'survey_time_period'     => "Rückmeldezeitraum",
        'survey_time_periods'    => "Rückmeldezeiträume",
        'survey_time_period_id'  => "Rückmeldezeitraum",
        'survey_time_period_ids' => "Rückmeldezeiträume",

        'id'                         => "ID",
        'uuid'                       => "UUID",
        'email'                      => "Email",
        'password'                   => "Passwort",
        'token'                      => "Zeichen",
        'first_name'                 => "Vorname",
        'last_name'                  => "Nachname",
        'two_factor_code'            => "2FA (Zwei-Faktor-Authentifizierung) Bestätigungscode",
        'current_password'           => "Aktuelles Passwort",
        'two_factor_auth_enabled'    => "2FA (Zwei-Faktor-Authentifizierung) Aktiviert",
        'name'                       => "Name",
        'abbreviation'               => "Kürzel",
        'order'                      => "Befehl",
        'age_2_red_threshold'        => "Altersgruppe bis 2,5 Jahre: Schwellwert Rot",
        'age_2_red_threshold_daz'    => "Altersgruppe bis 2,5 Jahre: Schwellwert Rot mit Daz",
        'age_2_yellow_threshold'     => "Altersgruppe bis 2,5 Jahre: Schwellwert Gelb",
        'age_2_yellow_threshold_daz' => "Altersgruppe bis 2,5 Jahre: Schwellwert Gelb",
        'age_4_red_threshold'        => "Altersgruppe bis 4,5 Jahre: Schwellwert Rot",
        'age_4_red_threshold_daz'    => "Altersgruppe bis 4,5 Jahre: Schwellwert Rot mit Daz",
        'age_4_yellow_threshold'     => "Altersgruppe bis 4,5 Jahre: Schwellwert Gelb",
        'age_4_yellow_threshold_daz' => "Altersgruppe bis 4,5 Jahre: Schwellwert Gelb mit Daz",
        'title'                      => "Titel",
        'emphasis'                   => "Gewichtung",
        'emphasis_daz'               => "Gewichtung mit Daz",
        'age'                        => "Altersgruppe",
        'zip_code'                   => "Postleitzahl",
        'data'                       => "Daten",
        'value'                      => "Wert",
        'ratings'                    => "Bewertungen",
        'child_duration_in_kita'     => "Zeitraum in der Kita",
        'integration_status'         => "Integrationsstatus",
        'speech_therapy_status'      => "in logopädische Behandlung",
        'year'                       => "Jahr",
        'age_year'                   => "Altersgruppe (Jahre)",
        'survey_start_date'          => "Erhebungsbeginn",
        'survey_end_date'            => "Erhebungsende",

        // 'parent_field.*.child_field' => "Parent field #:counter child field",
    ],

];
