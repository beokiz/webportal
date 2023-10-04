<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */

    /*
     * More: https://github.com/KnpLabs/snappy/blob/master/src/Knp/Snappy/Pdf.php#L86
     */
    'pdf'   => [
        'enabled' => true,
        'binary'  => env('WKHTML_PDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
        'timeout' => 900,
        'options' => [
            'enable-javascript'        => true,
            'javascript-delay'         => 0, // 5000
            'no-stop-slow-scripts'     => true,
            'enable-local-file-access' => true,
            'enable-smart-shrinking'   => true,
            'encoding'                 => 'UTF-8',
            // 'lowquality'               => false,
            'margin-top'               => 10,
            'margin-right'             => 10,
            'margin-bottom'            => 10,
            'margin-left'              => 10,
        ],
        'env'     => [],
    ],

    /*
     * More: https://github.com/KnpLabs/snappy/blob/master/src/Knp/Snappy/Image.php#L28
     */
    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', '/usr/local/bin/wkhtmltoimage'),
        'timeout' => 900,
        'options' => [
            'enable-javascript'        => true,
            'javascript-delay'         => 5000,
            'no-stop-slow-scripts'     => true,
            'enable-local-file-access' => true,
            'encoding'                 => 'UTF-8',
            'quality'                  => 100,
        ],
        'env'     => [],
    ],

];
