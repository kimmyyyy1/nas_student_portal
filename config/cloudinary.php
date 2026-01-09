<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (FINAL FIX)
|--------------------------------------------------------------------------
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
    | Cloud Settings
    |--------------------------------------------------------------------------
    | UPDATED: Naglagay tayo ng 'key' at 'secret' aliases dito.
    | Ito ang kulang kaya ayaw gumana kahit okay na yung filesystems.
    */
    'cloud' => [
        'cloud_name' => 'dqkzofruk',
        'api_key'    => '452544782214523',
        'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
        
        // 👇 ADD THESE ALIASES (Para sa "Undefined array key 'key'" error)
        'key'        => '452544782214523',
        'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
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