<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Exceptions Lines
    |--------------------------------------------------------------------------
    */

    'http_204_name'      => "Kein Inhalt",
    'http_400_name'      => "Ungültige Anfrage",
    'http_401_name'      => "Unberechtigt",
    'http_403_name'      => "Verboten",
    'http_404_name'      => "Nicht gefunden",
    'http_404_item_name' => "Artikel nicht gefunden",
    'http_404_page_name' => "Seite nicht gefunden",
    'http_405_name'      => "Methode nicht erlaubt",
    'http_413_name'      => "Fehler! Übermittelte Daten sind zu umfangreich.",
    'http_419_name'      => "Seite abgelaufen",
    'http_422_name'      => "Eingabedaten nicht akzeptabel",
    'http_429_name'      => "Zu viele Anfragen",
    'http_500_name'      => "Interner Serverfehler",
    'http_503_name'      => "Dienst nicht verfügbar",

    'http_204_msg'      => "Fehler! Kein Inhalt gefunden.",
    'http_400_msg'      => "Fehler! Schlechte Anfrage.",
    'http_401_msg'      => "Fehler! Sie müssen angemeldet sein, um diese Anfrage zu stellen.",
    'http_403_msg'      => "Fehler! Zugriff verweigert.",
    'http_404_msg'      => "Fehler! Methode nicht gefunden.",
    'http_404_item_msg' => "Fehler! Artikel nicht gefunden.",
    'http_404_page_msg' => "Fehler! Seite nicht gefunden.",
    'http_405_msg'      => "Fehler! Die Methode :method wird für diese Route nicht unterstützt. Unterstützte Methoden: :methods.",
    'http_413_msg'      => "Fehler! Payload zu groß.",
    'http_419_msg'      => "Fehler! Seite abgelaufen.",
    'http_422_msg'      => "Fehler! Die übergebenen Daten waren ungültig.",
    'http_429_msg'      => "Fehler! Zu viele Anfragen, versuchen Sie es später erneut.",
    'http_500_msg'      => "Interner Serverfehler, versuchen Sie es später erneut.",
    'http_503_msg'      => "Fehler! Dienst nicht verfügbar.",

    /*
    |--------------------------------------------------------------------------
    | Custom Exceptions Lines
    |--------------------------------------------------------------------------
    */

    '2fa_default_error' => 'Fehler! Bitte versuchen Sie es später noch einmal.',

    'cannot_update_selected_item' => "Fehler! Sie können das ausgewählte Element nicht aktualisieren.",
    'cannot_delete_selected_item' => "Fehler! Sie können das ausgewählte Element nicht löschen.",
    'cannot_update_selected_user' => "Fehler! Sie können den ausgewählten Benutzer nicht aktualisieren.",
    'cannot_delete_selected_user' => "Fehler! Sie können den ausgewählten Benutzer nicht löschen.",

    'user_does_not_have_access' => "Fehler! Sie haben keinen Zugriff auf diese Seite oder Aktion.",

    'user_does_not_have_the_required_roles_exception_msg' => "Um die ausgewählte Seite anzuzeigen, müssen Sie eine der Rollen haben: :roles.",

    'service_not_configured_exception_msg' => "Fehler! Der Dienst \":name\" ist nicht konfiguriert.",

];
