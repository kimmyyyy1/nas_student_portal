<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (REQUIRED FIX)
|--------------------------------------------------------------------------
| Ito ang file na hinahanap ng error na "Undefined array key cloud".
| Hiwalay ito sa filesystems.php. Dito, KAILANGAN ng 'cloud' array.
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud URL
    |--------------------------------------------------------------------------
    */
    'cloud_url' => 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk',

    /*
    |--------------------------------------------------------------------------
    | Cloud Settings (ITO ANG KULANG!)
    |--------------------------------------------------------------------------
    | Dito sa file na ito, REQUIRED na naka-loob sa 'cloud' array ang settings.
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
    'notification_url' => null,
    'upload_preset' => null,
    'upload_route' => null,
    'upload_action' => null,
];