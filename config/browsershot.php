<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Browsershot PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | Puppeteer options : https://github.com/puppeteer/puppeteer/blob/main/docs/api.md#pagepdfoptions
    | PDF options       : https://spatie.be/docs/browsershot/v2/usage/creating-pdfs
    | Image options     : https://spatie.be/docs/browsershot/v2/usage/creating-images
    |
    */

    'pdf' => [
        'landscape' => false,
        'timeout'   => 900,
        'format'    => 'A4',
        'margins'   => [
            'top'    => 10,
            'right'  => 10,
            'bottom' => 10,
            'left'   => 10,
        ],
    ],

    'image' => [
        'landscape' => false,
        'timeout'   => 900,
        'type'      => 'jpeg',
        'quality'   => 100,
    ],

];
