<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (Manual Setup)
|--------------------------------------------------------------------------
|
| Ang script na ito ay automatic na magpa-parse ng CLOUDINARY_URL
| para hindi na tayo mag-set ng hiwalay na API keys.
|
*/

$cloudUrl = env('CLOUDINARY_URL');

// Default values
$cloudName = null;
$apiKey = null;
$apiSecret = null;

// Kung may URL, i-breakdown natin
if ($cloudUrl && str_starts_with($cloudUrl, 'cloudinary://')) {
    $parsed = parse_url($cloudUrl);
    if ($parsed) {
        $cloudName = $parsed['host'] ?? null;
        $apiKey = $parsed['user'] ?? null;
        $apiSecret = $parsed['pass'] ?? null;
    }
}

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud URL
    |--------------------------------------------------------------------------
    */
    'cloud_url' => $cloudUrl,

    /*
    |--------------------------------------------------------------------------
    | Cloud Settings (ITO ANG HINAHANAP NG ERROR MO!)
    |--------------------------------------------------------------------------
    | Dito natin ipapasa ang na-parse na data.
    */
    'cloud' => [
        'cloud_name' => $cloudName,
        'api_key'    => $apiKey,
        'api_secret' => $apiSecret,
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