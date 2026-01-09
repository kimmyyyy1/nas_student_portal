<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration (Hardcoded Root)
    |--------------------------------------------------------------------------
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    // 👇 ITO ANG MAHALAGA. Hardcoded credentials para siguradong mabasa.
    'cloud' => [
        'cloud_name' => 'dqkzofruk', 
        'api_key'    => '452544782214523', 
        'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
    ],

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

];