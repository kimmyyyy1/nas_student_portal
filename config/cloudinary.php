<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (Global)
|--------------------------------------------------------------------------
| Ito ang file na binabasa ng ServiceProvider bago ang filesystem.
| Kailangan mag-match ito sa settings ng filesystems.php
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud Settings
    |--------------------------------------------------------------------------
    */
    
    // 1. STANDARD CREDENTIALS
    'cloud_name' => 'dqkzofruk',
    'api_key'    => '452544782214523',
    'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',

    // 2. THE CRITICAL FIX: ALIASES
    // Ito ang kulang dito kaya nag-e-error ng "Undefined array key 'key'"
    'key'        => '452544782214523', 
    'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',

    // 3. CLOUD URL
    'cloud_url'  => 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk',

    // 4. OTHER SETTINGS
    'notification_url' => null,
    'upload_preset' => null,
    'upload_route' => null,
    'upload_action' => null,

    // ❌ IMPORTANTE: TANGGALIN NA ANG 'cloud' => [...] ARRAY
    // Para maiwasan ang "TypeError" na nangyari kanina.
];