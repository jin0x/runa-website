<?php

return [
    'default' => 'theme',
    'manifests' => [
        'theme' => [
            'path' => get_theme_file_path('public/build'),
            'url' => get_theme_file_uri('public/build'),
            'assets' => get_theme_file_path('public/build/manifest.json'),
            'bundles' => get_theme_file_path('public/build/manifest.json'), // Use manifest.json for Vite instead of entrypoints.json
        ],
    ],
];