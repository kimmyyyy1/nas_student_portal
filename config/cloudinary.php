<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | An HTTP or HTTPS URL to notify your application (a webhook) when the process of uploads,
    | deletes, and any API that accepts notification_url has completed.
    |
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud hosted
    | media management service for all file uploads, storage, delivery and transformation needs.
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /**
    * Upload Preset From Cloudinary Dashboard
    *
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Credentials
    |--------------------------------------------------------------------------
    |
    | 👇👇👇 ITO ANG NAWAWALA KAYA NAG-EERROR 👇👇👇
    | The following keys are used if you are not using the CLOUDINARY_URL configuration.
    |
    */
    'cloud' => [
        'cloud_name' => env('dqkzofruk'),
        'api_key'    => env('452544782214523'),
        'api_secret' => env('Dew-wu6KDw8HNKzO473L5P5tpqo'),
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