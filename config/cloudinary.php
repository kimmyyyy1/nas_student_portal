<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration (Hardcoded "Untitled" Key)
    |--------------------------------------------------------------------------
    */

    // Iwanan itong blanko dahil iha-hardcode natin sa baba
    'cloud_url' => env('CLOUDINARY_URL'),

    'cloud' => [
        'cloud_name' => 'dqkzofruk', 
        'api_key'    => '681411283875527', // Ito ang API Key ng "Untitled"
        'api_secret' => 'Q6SMPHbhLkJaKtzGZ7atZmXRwGE', // 👈 COPY-PASTE MO DITO YUNG SECRET NG "UNTITLED"
    ],

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

];