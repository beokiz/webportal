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
        'start_message'         => "Starte DB-Import...",
        'success_message'       => "Erfolg! Die ausgewählte Datei wurde erfolgreich importiert.",
        'missing_path_argument' => "Fehler! Die Option \"path\" ist erforderlich.",
        'error_message'         => "Fehler! Die ausgewählte Datei wurde nicht importiert.",
    ],

    'version_update' => [
        'success_message'       => "Erfolg! Version der Anwendung aktualisiert auf: :version.",
        'file_error_message'    => "Fehler! Datei :filepath nicht gefunden.",
        'version_error_message' => "Fehler! Ungültiges Versionsformat",
    ],

    'gk_supervisor' => [
        'success_message'         => "Erfolg! Der Befehl erfolgreich ausgeführt.",
        'invalid_action_argument' => "Fehler! Die Option \"action\" ist ungültig.",
    ],

    'gk_temp' => [
        'clear_tmp_message' => ":count temporäre Datei(en) des Projekts gelöscht!",
    ],

];
