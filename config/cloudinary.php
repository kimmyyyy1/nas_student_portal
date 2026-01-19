<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /**
    * Upload Preset From Cloudinary Dashboard
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Credentials
    |--------------------------------------------------------------------------
    | 👇 FIXED: Tinanggal ang env() at nilagay directly ang values (Strings)
    */
    'cloud' => [
        'cloud_name' => 'dqkzofruk', 
        'api_key'    => '452544782214523', 
        'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo', 
    ],

    /*
    |--------------------------------------------------------------------------
    | Other Settings
    |--------------------------------------------------------------------------
    */
    'home' => env('CLOUDINARY_HOME'),
    'secure_distribution' => env('CLOUDINARY_SECURE_DISTRIBUTION'),
    'private_cdn' => env('CLOUDINARY_PRIVATE_CDN'),
    'cname' => env('CLOUDINARY_CNAME'),
    'cdn_subdomain' => env('CLOUDINARY_CDN_SUBDOMAIN'),
    'shorten' => env('CLOUDINARY_SHORTEN'),
    'secure' => env('CLOUDINARY_SECURE'),
    'use_root_path' => env('CLOUDINARY_USE_ROOT_PATH'),
    'disable_cloudinary_url' => env('CLOUDINARY_DISABLE_URL'),
];