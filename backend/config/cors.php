<?php

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
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'http://localhost:8081'],
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'],
    'allowedMethods'         => ['DELETE', 'GET', 'POST', 'PUT'],
    'allowedOrigins'         => ['http://localhost:8081'],
    //'Access-Control-Allow-Credentials' => true,
    //'allowedOriginsPatterns' => ['/localhost:\d/'],
    'exposedHeaders'         => ['*'],
    'maxAge'                 => 0,
    'supportsCredentials'    => true,
];
