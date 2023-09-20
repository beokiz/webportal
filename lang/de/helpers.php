<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Helper Functions Text Lines
    |--------------------------------------------------------------------------
    */

//    // some_helper
//    'some_helper_msg' => "",

    /*
    |--------------------------------------------------------------------------
    | Helper Methods Text Lines
    |--------------------------------------------------------------------------
    */

    // StubGeneratorHelper
    'stub_generator_success'                      => "Erfolg! Der Pfad zur Datei: :path.",
    'stub_generator_error'                        => "Fehler! Etwas ist schief gelaufen, Datei :filename wurde nicht erstellt.",
    'stub_generator_stub_not_exist_error'         => "Fehler! Die Stub-Datei \":path\" existiert nicht.",
    'stub_generator_file_already_exists_error'    => "Fehler! Die Datei \":filename\" existiert bereits.",

    // MysqlDatabaseHelper: common
    'mysql_db_helper_connection_error'            => "Fehler! Die Datenbankverbindung existiert nicht.",

    // MysqlDatabaseHelper: export
    'mysql_db_helper_export_path_not_exist_error' => "Fehler! Das ausgewählte Verzeichnis existiert nicht.",

    // MysqlDatabaseHelper: import
    'mysql_db_helper_import_file_not_exist_error' => "Fehler! Die Datei am angegebenen Pfad existiert nicht.",
    'mysql_db_helper_import_file_wrong_ext_error' => "Fehler! Die ausgewählte Datei konnte nicht importiert werden.",

];
