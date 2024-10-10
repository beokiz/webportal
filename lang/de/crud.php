<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Common CRUD Lines
    |--------------------------------------------------------------------------
    */

    'common' => [
        'create_success' => "Der Artikel wurde erfolgreich erstellt.",
        'create_error'   => "Fehler! Beim Erstellen des Artikels ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success' => "Der Artikel wurde erfolgreich aktualisiert.",
        'update_error'   => "Fehler! Beim Aktualisieren des Artikels ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'  => "Fehler! Der ausgewählte Artikel kann nicht aktualisiert werden.",
        'delete_success' => "Der Artikel wurde erfolgreich gelöscht.",
        'delete_error'   => "Fehler! Beim Löschen des Artikels ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'  => "Fehler! Der ausgewählte Artikel kann nicht gelöscht werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Users CRUD Lines
    |--------------------------------------------------------------------------
    */

    'users' => [
        'create_success'                 => "Der Benutzer wurde erfolgreich erstellt.",
        'create_error'                   => "Fehler! Beim Erstellen des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'                 => "Der Benutzer wurde erfolgreich aktualisiert.",
        'update_error'                   => "Fehler! Beim Aktualisieren des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'                  => "Fehler! Der ausgewählte Benutzer kann nicht aktualisiert werden.",
        'send_verification_link_success' => "Der Bestätigungslink wurde erfolgreich gesendet.",
        'send_verification_link_error'   => "Fehler! Der Bestätigungslink konnte nicht gesendet werden. Bitte versuchen Sie es später erneut.",
        'send_verification_link_denied'  => "Fehler! Die E-Mail wurde bereits verifiziert.",
        'delete_success'                 => "Der Benutzer wurde erfolgreich gelöscht.",
        'delete_error'                   => "Fehler! Beim Löschen des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'                  => "Fehler! Der ausgewählte Benutzer kann nicht gelöscht werden.",
        'restore_success'                => "Der Benutzer wurde erfolgreich wiederhergestellt.",
        'restore_error'                  => "Fehler! Beim Wiederherstellen des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'                 => "Fehler! Der ausgewählte Benutzer kann nicht wiederhergestellt werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Domains CRUD Lines
    |--------------------------------------------------------------------------
    */

    'domains' => [
        'create_success'  => "Die Domain wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Bei der Erstellung der Domain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Die Domain wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Bei der Aktualisierung der Domain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Die ausgewählte Domain kann nicht aktualisiert werden.",
        'delete_success'  => "Die Domain wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Bei der Löschung der Domain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Die ausgewählte Domain kann nicht gelöscht werden.",
        'restore_success' => "Die Domain wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Bei der Wiederherstellung der Domain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Die ausgewählte Domain kann nicht wiederhergestellt werden.",
        'reorder_success' => "Domains wurden erfolgreich neu angeordnet.",
        'reorder_error'   => "Fehler! Beim Neuordnen der Domains ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Subdomains CRUD Lines
    |--------------------------------------------------------------------------
    */

    'subdomains' => [
        'create_success'  => "Die Subdomain wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Bei der Erstellung der Subdomain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Die Subdomain wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Bei der Aktualisierung der Subdomain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Die ausgewählte Subdomain kann nicht aktualisiert werden.",
        'delete_success'  => "Die Subdomain wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Bei der Löschung der Subdomain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Die ausgewählte Subdomain kann nicht gelöscht werden.",
        'restore_success' => "Die Subdomain wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Bei der Wiederherstellung der Subdomain ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Die ausgewählte Subdomain kann nicht wiederhergestellt werden.",
        'reorder_success' => "Subdomains wurden erfolgreich neu angeordnet.",
        'reorder_error'   => "Fehler! Beim Neuanordnen der Subdomains ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Milestones CRUD Lines
    |--------------------------------------------------------------------------
    */

    'milestones' => [
        'create_success'  => "Meilenstein wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Beim Erstellen eines Meilensteins ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Meilenstein wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Beim Aktualisieren eines Meilensteins ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Ausgewählter Meilenstein kann nicht aktualisiert werden.",
        'delete_success'  => "Meilenstein wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Beim Löschen eines Meilensteins ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Ausgewählter Meilenstein kann nicht gelöscht werden.",
        'restore_success' => "Meilenstein wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Beim Wiederherstellen eines Meilensteins ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Ausgewählter Meilenstein kann nicht wiederhergestellt werden.",
        'reorder_success' => "Meilensteine wurden erfolgreich neu angeordnet.",
        'reorder_error'   => "Fehler! Beim Neuanordnen der Meilensteine ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Kitas CRUD Lines
    |--------------------------------------------------------------------------
    */

    'kitas' => [
        'create_success'      => "Einrichtung wurde erfolgreich erstellt.",
        'create_error'        => "Fehler! Beim Erstellen eines Einrichtung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'      => "Einrichtung wurde erfolgreich aktualisiert.",
        'update_error'        => "Fehler! Beim Aktualisieren eines Einrichtung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'       => "Fehler! Ausgewählter Einrichtung kann nicht aktualisiert werden.",
        'delete_success'      => "Einrichtung wurde erfolgreich gelöscht.",
        'delete_error'        => "Fehler! Beim Löschen eines Einrichtung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'       => "Fehler! Ausgewählter Einrichtung kann nicht gelöscht werden.",
        'delete_users_denied' => "Fehler! Der ausgewählten Einrichtung sind Benutzer zugeordnet, daher kann sie nicht gelöscht werden.",
        'restore_success'     => "Einrichtung wurde erfolgreich wiederhergestellt.",
        'restore_error'       => "Fehler! Beim Wiederherstellen eines Einrichtung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'      => "Fehler! Ausgewählter Einrichtung kann nicht wiederhergestellt werden.",
        'reorder_success'     => "Einrichtungen wurden erfolgreich neu angeordnet.",
        'reorder_error'       => "Fehler! Beim Neuanordnen der Einrichtungen ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Evaluation CRUD Lines
    |--------------------------------------------------------------------------
    */

    'evaluations' => [
        'create_success'      => "Die Bewertung wurde erfolgreich erstellt.",
        'create_error'        => "Fehler! Etwas ist beim Erstellen einer Bewertung schiefgegangen. Bitte versuchen Sie es später erneut.",
        'update_success'      => "Die Bewertung wurde erfolgreich aktualisiert.",
        'update_error'        => "Fehler! Etwas ist beim Aktualisieren einer Bewertung schiefgegangen. Bitte versuchen Sie es später erneut.",
        'update_denied'       => "Fehler! Die ausgewählte Bewertung kann nicht aktualisiert werden.",
        'save_success'        => "Die Bewertung wurde erfolgreich gespeichert.",
        'save_error'          => "Fehler! Etwas ist beim Speichern einer Bewertung schiefgegangen. Bitte versuchen Sie es später erneut.",
        'save_denied'         => "Fehler! Die ausgewählte Bewertung kann nicht gespeichert werden.",
        'check_success'       => "Die Auswertung wurde erfolgreich geprüft.",
        'check_error'         => "Fehler! Beim Überprüfen einer Bewertung ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
        'delete_success'      => "Die Bewertung wurde erfolgreich gelöscht.",
        'delete_error'        => "Fehler! Etwas ist beim Löschen einer Bewertung schiefgegangen. Bitte versuchen Sie es später erneut.",
        'delete_denied'       => "Fehler! Die ausgewählte Bewertung kann nicht gelöscht werden.",
        'delete_users_denied' => "Fehler! Die ausgewählte Bewertung hat zugehörige Benutzer und kann daher nicht gelöscht werden.",
        'restore_success'     => "Die Bewertung wurde erfolgreich wiederhergestellt.",
        'restore_error'       => "Fehler! Etwas ist beim Wiederherstellen einer Bewertung schiefgegangen. Bitte versuchen Sie es später erneut.",
        'restore_denied'      => "Fehler! Die ausgewählte Bewertung kann nicht wiederhergestellt werden.",
        'pdf_error'           => "Fehler! Beim Erstellen der Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'export_error'        => "Fehler! Beim Erstellen der Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Survey Time Period CRUD Lines
    |--------------------------------------------------------------------------
    */

    'survey_time_periods' => [
        'create_success'  => "Rückmeldezeitraum wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Beim Erstellen eines Rückmeldezeitraum ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Rückmeldezeitraum wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Beim Aktualisieren eines Rückmeldezeitraum ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Ausgewählter Rückmeldezeitraum kann nicht aktualisiert werden.",
        'delete_success'  => "Rückmeldezeitraum wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Beim Löschen eines Rückmeldezeitraum ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Ausgewählter Rückmeldezeitraum kann nicht gelöscht werden.",
        'restore_success' => "Rückmeldezeitraum wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Beim Wiederherstellen eines Rückmeldezeitraum ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Ausgewählter Rückmeldezeitraum kann nicht wiederhergestellt werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Yearly Evaluation CRUD Lines
    |--------------------------------------------------------------------------
    */

    'yearly_evaluations' => [
        'create_success'  => "Jährliche Rückmeldung wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Beim Erstellen eines Jährliche Rückmeldung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Jährliche Rückmeldung wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Beim Aktualisieren eines Jährliche Rückmeldung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Ausgewählter Jährliche Rückmeldung kann nicht aktualisiert werden.",
        'delete_success'  => "Jährliche Rückmeldung wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Beim Löschen eines Jährliche Rückmeldung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Ausgewählter Jährliche Rückmeldung kann nicht gelöscht werden.",
        'restore_success' => "Jährliche Rückmeldung wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Beim Wiederherstellen eines Jährliche Rückmeldung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Ausgewählter Jährliche Rückmeldung kann nicht wiederhergestellt werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Setting CRUD Lines
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'update_success' => "Die Einstellung wurde erfolgreich aktualisiert.",
        'update_error'   => "Fehler! Beim Aktualisieren einer Einstellung ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
        'update_denied'  => "Fehler! Die ausgewählte Einstellung kann nicht aktualisiert werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Downloadable Files CRUD Lines
    |--------------------------------------------------------------------------
    */

    'downloadable_files' => [
        'create_success'  => "Die herunterladbare Datei wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Beim Erstellen der herunterladbaren Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
        'update_success'  => "Die herunterladbare Datei wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Beim Aktualisieren der herunterladbaren Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
        'update_denied'   => "Fehler! Die ausgewählte herunterladbare Datei kann nicht aktualisiert werden.",
        'delete_success'  => "Die herunterladbare Datei wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Beim Löschen der herunterladbaren Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
        'delete_denied'   => "Fehler! Die ausgewählte herunterladbare Datei kann nicht gelöscht werden.",
        'restore_success' => "Die herunterladbare Datei wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Beim Wiederherstellen der herunterladbaren Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.",
        'restore_denied'  => "Fehler! Die ausgewählte herunterladbare Datei kann nicht wiederhergestellt werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Operators CRUD Lines
    |--------------------------------------------------------------------------
    */

    'operators' => [
        'create_success'  => "Der Betreiber wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Beim Erstellen eines Betreibers ist etwas schief gelaufen. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Der Betreiber wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Beim Aktualisieren eines Betreibers ist etwas schief gelaufen. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Der ausgewählte Betreiber kann nicht aktualisiert werden.",
        'delete_success'  => "Der Betreiber wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Beim Löschen eines Betreibers ist etwas schief gelaufen. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Der ausgewählte Betreiber kann nicht gelöscht werden.",
        'restore_success' => "Der Betreiber wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Beim Wiederherstellen eines Betreibers ist etwas schief gelaufen. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Der ausgewählte Betreiber kann nicht wiederhergestellt werden.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Trainings CRUD Lines
    |--------------------------------------------------------------------------
    */

    'trainings' => [
        'create_success'  => "Das Training wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Bei der Erstellung des Trainings ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Das Training wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Bei der Aktualisierung des Trainings ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Das ausgewählte Training kann nicht aktualisiert werden.",
        'delete_success'  => "Das Training wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Bei der Löschung des Trainings ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Das ausgewählte Training kann nicht gelöscht werden.",
        'restore_success' => "Das Training wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Bei der Wiederherstellung des Trainings ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Das ausgewählte Training kann nicht wiederhergestellt werden.",

        'choose_location'  => "[Ort bei einer der Kitas erfragen!]",
    ],

    /*
    |--------------------------------------------------------------------------
    | Training Proposals CRUD Lines
    |--------------------------------------------------------------------------
    */

    'training_proposals' => [
        'create_success'  => "Der Trainingsvorschlag wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Bei der Erstellung des Trainingsvorschlags ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Der Trainingsvorschlag wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Bei der Aktualisierung des Trainingsvorschlags ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Der ausgewählte Trainingsvorschlag kann nicht aktualisiert werden.",
        'confirm_success' => "Der Trainingsvorschlag wurde erfolgreich bestätigt.",
        'confirm_error'   => "Fehler! Bei der Bestätigung des Trainingsvorschlags ist etwas schiefgelaufen. Bitte versuchen Sie es später erneut.",
        'delete_success'  => "Der Trainingsvorschlag wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Bei der Löschung des Trainingsvorschlags ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Der ausgewählte Trainingsvorschlag kann nicht gelöscht werden.",
        'restore_success' => "Der Trainingsvorschlag wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Bei der Wiederherstellung des Trainingsvorschlags ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Der ausgewählte Trainingsvorschlag kann nicht wiederhergestellt werden.",
    ],

];
