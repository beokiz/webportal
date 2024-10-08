<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3'        => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_URL'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        /*
         * Custom
         */
        'local_tmp' => [
            'driver'      => 'local',
            'root'        => storage_path('app/tmp'),
            'permissions' => [
                'file' => [
                    'public'  => 0664,
                    'private' => 0600,
                ],
                'dir'  => [
                    'public'  => 0755,
                    'private' => 0700,
                ],
            ],
        ],

        'public_files' => [
            'driver'      => 'local',
            'root'        => storage_path('app/public/files'),
            'permissions' => [
                'file' => [
                    'public'  => 0664,
                    'private' => 0600,
                ],
                'dir'  => [
                    'public'  => 0755,
                    'private' => 0700,
                ],
            ],
        ],

        'import_files' => [
            'driver'      => 'local',
            'root'        => storage_path('app/import'),
            'permissions' => [
                'file' => [
                    'public'  => 0664,
                    'private' => 0600,
                ],
                'dir'  => [
                    'public'  => 0755,
                    'private' => 0700,
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
