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
        'create_success'  => "Der Benutzer wurde erfolgreich erstellt.",
        'create_error'    => "Fehler! Beim Erstellen des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_success'  => "Der Benutzer wurde erfolgreich aktualisiert.",
        'update_error'    => "Fehler! Beim Aktualisieren des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'update_denied'   => "Fehler! Der ausgewählte Benutzer kann nicht aktualisiert werden.",
        'delete_success'  => "Der Benutzer wurde erfolgreich gelöscht.",
        'delete_error'    => "Fehler! Beim Löschen des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'delete_denied'   => "Fehler! Der ausgewählte Benutzer kann nicht gelöscht werden.",
        'restore_success' => "Der Benutzer wurde erfolgreich wiederhergestellt.",
        'restore_error'   => "Fehler! Beim Wiederherstellen des Benutzers ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.",
        'restore_denied'  => "Fehler! Der ausgewählte Benutzer kann nicht wiederhergestellt werden.",
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

];
