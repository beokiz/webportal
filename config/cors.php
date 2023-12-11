<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths'                    => ['api/*', 'broadcasting/*', 'webhooks/*', 'sanctum/csrf-cookie'],
    'allowed_methods'          => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
    'allowed_origins'          => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers'          => ['X-Xsrf-Token', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization', 'X-Token-Auth', 'X-User-Timezone'],
    'exposed_headers'          => ['*', 'Authorization'],
    'max_age'                  => 0,
    'supports_credentials'     => true,

];
