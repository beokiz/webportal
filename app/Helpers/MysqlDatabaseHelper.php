<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Helpers;

use App\Exceptions\Custom\DatabaseHelperException;


/**
 * MySQL Database Helper
 *
 * @package \App\Helpers
 */
class MysqlDatabaseHelper
{
    /**
     * MysqlDatabaseHelper constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string      $connectionName
     * @param array       $options
     * @param string|null $pathToSave
     * @return mixed
     */
    public static function export(string $connectionName, array $options = [], ?string $pathToSave = null)
    {
        // If is called from tenant, and '$connectionName' passed as main connection - connection data will be automatically replaced to tenant connection
        $connectionData = config("database.connections.{$connectionName}");

        $ds = DIRECTORY_SEPARATOR;
        $ts = time();

        // Check if selected connection exist
        if (empty($connectionData)) {
            throw new DatabaseHelperException(__('helpers.mysql_db_helper_connection_error'));
        }

        // Check selected path exist
        if ($pathToSave) {
            if (!is_dir($pathToSave)) {
                throw new DatabaseHelperException(__('helpers.mysql_db_helper_export_path_not_exist_error'));
            }

            $pathToSave .= $ds;
        } else {
            $pathToSave = database_path(
                'backups' . $ds . date('Y', $ts) . $ds . date('m', $ts) . $ds . date('d', $ts) . $ds
            );

            is_dir($pathToSave) ?: mkdir($pathToSave, 0755, true);
        }

        // Prepare file attributes
        $fileName = date('Y-m-d-His', $ts) . '-dump-' . $connectionData['database'] . '.sql';
        $filePath = $pathToSave . $fileName;

        // Prepare export command
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            $connectionData['host'], $connectionData['username'], $connectionData['password'], $connectionData['database'], $filePath
        );

        if (!empty($options)) {
            $command .= ' ' . implode(' ', $options);
        }

        // Execute export command
        exec($command);

        return file_exists($filePath) ? $filePath : false;
    }

    /**
     * @param string $connectionName
     * @param string $pathToFile
     * @return void
     */
    public static function import(string $connectionName, string $pathToFile)
    {
        // If is called from tenant, and '$connectionName' passed as main connection - connection data will be automatically replaced to tenant connection
        $connectionData = config("database.connections.{$connectionName}");

        // Check if selected connection exist
        if (empty($connectionData)) {
            throw new DatabaseHelperException(__('helpers.mysql_db_helper_connection_error'));
        }

        // Check if selected file exist
        if (!file_exists($pathToFile)) {
            throw new DatabaseHelperException(__('helpers.mysql_db_helper_import_file_not_exist_error'));
        }

        // Check if selected file can be imported
        if (pathinfo($pathToFile, PATHINFO_EXTENSION) !== 'sql') {
            throw new DatabaseHelperException(__('helpers.mysql_db_helper_import_file_wrong_ext_error'));
        }

        // Prepare export command
        $command = sprintf(
            'mysql -h %s -u %s -p%s %s < %s',
            $connectionData['host'], $connectionData['username'], $connectionData['password'], $connectionData['database'], $pathToFile
        );

        // Execute export command
        exec($command);
    }
}
