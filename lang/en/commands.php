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
        'start_message'                 => "Starting DB import...",
        'success_message'               => "Success! The selected file was successfully imported.",
        'missing_path_argument_message' => "Error! The \"path\" option is required.",
        'error_message'                 => "Error! The selected file was not imported.",
    ],

    'version_update' => [
        'success_message'       => "Success! Updated version app to: :version.",
        'file_error_message'    => "Error! File :filepath not found.",
        'version_error_message' => "Error! Invalid version format",
    ],

    'supervisor' => [
        'success_message'                 => "Success! The command was executed successfully.",
        'invalid_action_argument_message' => "Error! The \"action\" option is invalid.",
    ],

    'temp' => [
        'clear_tmp_message' => ":count project temporary file(s) deleted!",
    ],

    'clean' => [
        'start_message'               => "Starting cleanup of old backup folders...",
        'folder_deleted_message'      => "Deleted backup folder: :folder.",
        'folder_delete_fail_message'  => "Failed to delete backup folder: :folder.",
        'empty_month_deleted_message' => "Deleted empty month folder: :folder.",
        'empty_year_deleted_message'  => "Deleted empty year folder: :folder.",
        'cleanup_complete_message'    => "Old backup folders have been cleaned up.",
        'no_backup_found_message'     => "Backup directory does not exist.",
    ],

    'import_trainings' => [
        'start_message'          => "The file ':filename' does not exist",
        'file_not_exist_message' => "Error! Starting the import of file ':filename'...",
        'success_message'        => "Success! Training imported successfully from ':filename'.",
    ],

];
