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

    'paths' => [
        'api/*',
        'updatePassword/*',
        'updateHoursContract/*',
        'user/*', // en caso de usar rutas con /user/
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
    'https://fichajes-damia-front-5x5w6b09a-omareltahasantos-projects.vercel.app',
    'https://sistemasmedioambientalesfichajes.netlify.app',
    'http://localhost:3000',
    'https://localhost:8000',
    'https://smnetsistemasmedioambientales.herokuapp.com',
],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
