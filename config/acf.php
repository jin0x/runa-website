<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Field Type Settings
    |--------------------------------------------------------------------------
    |
    | Here you can set default field type settings that are automatically applied
    | to any field type when its initialized.
    |
    */

    'defaults' => [
        'trueFalse' => ['ui' => 1],
        'select' => ['ui' => 1],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Here you can enable or disable ACF Builder cache and set cache path.
    |
    */

    'cache' => [
        'enabled' => env('ACF_CACHE', false),
        'path' => storage_path('framework/cache/acf'),
    ],
];
