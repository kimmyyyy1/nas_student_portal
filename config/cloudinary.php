<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (Hardcoded Fix)
|--------------------------------------------------------------------------
| Ito ang file na hinahanap ng error. Hardcoded na ang credentials
| para siguradong mabasa ng system.
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
    | Cloud Settings (ITO ANG HINAHANAP NG ERROR!)
    |--------------------------------------------------------------------------
    | Manual setting ng credentials para mawala ang error.
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