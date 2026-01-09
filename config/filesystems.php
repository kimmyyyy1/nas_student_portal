<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        // 👇 DITO TAYO MAG-FOCUS: "KITCHEN SINK" CONFIG 👇
        'cloudinary' => [
            'driver' => 'cloudinary',
            
            // 1. URL Backup
            'cloud_url' => 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk',

            // 2. Standard Names (Ito ang karaniwan)
            'cloud_name' => 'dqkzofruk',
            'api_key'    => '452544782214523',
            'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',

            // 3. FIX SA "UNDEFINED ARRAY KEY 'key'" ERROR
            // Minsan, ang package ay naghahanap ng "key" at "secret" (gaya sa S3)
            'key'        => '452544782214523', 
            'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',

            // 4. FIX SA "UNDEFINED ARRAY KEY 'cloud'" ERROR
            // Ito naman ang para sa dating error, nested sa loob ng 'cloud' array
            'cloud' => [
                'cloud_name' => 'dqkzofruk',
                'api_key'    => '452544782214523',
                'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            ],
            
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];