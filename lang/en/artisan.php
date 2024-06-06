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
        'start'     => "The command was started...",
        'success'   => "The command successfully completed!",
        'error'     => "An error occurred while executing the command!",
        'exception' => "Exception message: :exception.",
    ],

    'database_export' => [
        'start_message'   => "Starting DB export...",
        'success_message' => "Success! The path to the file: :path.",
        'error_message'   => "Error! The dump file was not created.",
    ],

    'database_import' => [
        'start_message'         => "Starting DB import...",
        'success_message'       => "Success! The selected file was successfully imported.",
        'missing_path_argument' => "Error! The \"path\" option is required.",
        'error_message'         => "Error! The selected file was not imported.",
    ],

    'version_update' => [
        'success_message'       => "Success! Updated version app to: :version.",
        'file_error_message'    => "Error! File :filepath not found.",
        'version_error_message' => "Error! Invalid version format",
    ],

    'gk_supervisor' => [
        'success_message'         => "Success! The command was executed successfully.",
        'invalid_action_argument' => "Error! The \"action\" option is invalid.",
    ],

    'gk_temp' => [
        'clear_tmp_message' => ":count project temporary file(s) deleted!",
    ],

];
