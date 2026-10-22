<?php

// Minimal config: in a real project keep the channels that already exist in
// config/logging.php and only add the 'security' channel below.
return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', (string) env('LOG_STACK', 'single')),
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        // Dedicated channel for authentication and access-control events.
        'security' => [
            'driver' => 'daily',
            'path' => storage_path('logs/security.log'),
            'level' => 'info',
            'max_files' => 90, // retention in days (older Laravel versions call this key 'days')
            'replace_placeholders' => true,
        ],
    ],
];
