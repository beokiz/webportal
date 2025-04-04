<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Artisan Command Text Lines
    |--------------------------------------------------------------------------
    */

    'common' => [
        'start'     => "Der Befehl wurde gestartet...",
        'success'   => "Der Befehl wurde erfolgreich ausgeführt!",
        'error'     => "Es ist ein Fehler beim Ausführen des Befehls aufgetreten!",
        'exception' => "Fehlermeldung: :exception.",
    ],

    'database_export' => [
        'start_message'   => "Starte DB-Export...",
        'success_message' => "Erfolg! Pfad zur Datei: :path.",
        'error_message'   => "Fehler! Die Dump-Datei wurde nicht erstellt.",
    ],

    'database_import' => [
        'start_message'                 => "Starte DB-Import...",
        'success_message'               => "Erfolg! Die ausgewählte Datei wurde erfolgreich importiert.",
        'missing_path_argument_message' => "Fehler! Die Option \"path\" ist erforderlich.",
        'error_message'                 => "Fehler! Die ausgewählte Datei wurde nicht importiert.",
    ],

    'version_update' => [
        'success_message'       => "Erfolg! Version der Anwendung aktualisiert auf: :version.",
        'file_error_message'    => "Fehler! Datei :filepath nicht gefunden.",
        'version_error_message' => "Fehler! Ungültiges Versionsformat",
    ],

    'supervisor' => [
        'success_message'                 => "Erfolg! Der Befehl erfolgreich ausgeführt.",
        'invalid_action_argument_message' => "Fehler! Die Option \"action\" ist ungültig.",
    ],

    'temp' => [
        'clear_tmp_message' => ":count temporäre Datei(en) des Projekts gelöscht!",
    ],

    'clean' => [
        'start_message'               => "Bereinigung alter Backup-Ordner wird gestartet...",
        'folder_deleted_message'      => "Backup-Ordner gelöscht: :folder.",
        'folder_delete_fail_message'  => "Fehler beim Löschen des Backup-Ordners: :folder.",
        'empty_month_deleted_message' => "Leerer Monatsordner gelöscht: :folder.",
        'empty_year_deleted_message'  => "Leerer Jahresordner gelöscht: :folder.",
        'cleanup_complete_message'    => "Alte Backup-Ordner wurden bereinigt.",
        'no_backup_found_message'     => "Backup-Verzeichnis existiert nicht.",
    ],

    'import_trainings' => [
        'start_message'          => "Die Datei ':filename' existiert nicht",
        'file_not_exist_message' => "Fehler! Der Import der Datei ':filename' wird gestartet...",
        'success_message'        => "Erfolg! Das Training wurde erfolgreich aus der Datei ':filename' importiert.",
    ],

];
