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
    'stub_generator_success'                      => "Success! The path to the file: :path.",
    'stub_generator_error'                        => "Error! Something went wrong, file :filename not created.",
    'stub_generator_stub_not_exist_error'         => "Error! The stub file \":path\" does not exist.",
    'stub_generator_file_already_exists_error'    => "Error! The file \":filename\" already exists.",

    // MysqlDatabaseHelper: common
    'mysql_db_helper_connection_error'            => "Error! Database connection does not exist.",

    // MysqlDatabaseHelper: export
    'mysql_db_helper_export_path_not_exist_error' => "Error! The selected directory not exist.",

    // MysqlDatabaseHelper: import
    'mysql_db_helper_import_file_not_exist_error' => "Error! The file at the specified path does not exist.",
    'mysql_db_helper_import_file_wrong_ext_error' => "Error! The selected file could not be imported.",

];
