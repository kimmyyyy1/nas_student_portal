<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
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

        // 👇 VERCEL TEMP STORAGE FOR LIVEWIRE UPLOADS 👇
        'vercel_tmp' => [
            'driver' => 'local',
            'root' => '/tmp/storage/api/livewire-tmp',
        ],

        // 👇 ITO ANG FINAL FIX: FLAT CONFIGURATION 👇
        'cloudinary' => [
            'driver' => 'cloudinary',
            
            // 1. STANDARD CREDENTIALS
            'cloud_name' => 'dqkzofruk',
            'api_key'    => '452544782214523',
            'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',

            // 2. CRITICAL FIX: ALIASES
            // Ito ang hinahanap ng error na "Undefined array key 'key'".
            // Dapat nasa ROOT level sila (hindi naka-loob sa ibang array).
            'key'        => '452544782214523', 
            'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            
            // 3. BACKUP URL
            'cloud_url' => 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk',
            
            'throw' => false,
            
            // ❌ NOTE: Wala tayong inilagay na 'cloud' => [...] array dito
            // para iwasan ang TypeError.
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];