<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (Hardcoded for Vercel)
|--------------------------------------------------------------------------
| We are manually defining the 'cloud' array key to stop the error.
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud URL
    |--------------------------------------------------------------------------
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloud Settings (ITO ANG FIX)
    |--------------------------------------------------------------------------
    | Hardcoded credentials para sigurado.
    | NOTE: Base ito sa screenshot mo ng "Root" key.
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
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),
];