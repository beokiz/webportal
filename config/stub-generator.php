<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Stub Generator Settings
    |--------------------------------------------------------------------------
    */

    'stub_path' => [
        'interface' => base_path('stubs/interface.stub'),
        'service'   => base_path('stubs/service.stub'),
    ],

    'base_file_name' => [
        'interface' => '.php',
        'service'   => '.php',
    ],

    'namespace' => [
        'interface' => 'App\\Interfaces',
        'service'   => 'App\\Services',
    ],

];
