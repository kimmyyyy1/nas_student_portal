<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration (Auto-Parsing)
|--------------------------------------------------------------------------
|
| Ang script na ito ang bahalang kumuha ng API Key, Secret, at Cloud Name
| mula sa CLOUDINARY_URL na nasa Vercel environment variables mo.
|
*/

$cloudUrl = env('CLOUDINARY_URL');

// Default values (kung sakaling wala)
$cloudName = null;
$apiKey = null;
$apiSecret = null;

// Logic: Hatiin ang URL para makuha ang credentials
// Format: cloudinary://API_KEY:API_SECRET@CLOUD_NAME
if ($cloudUrl && str_contains($cloudUrl, 'cloudinary://')) {
    // Tanggalin ang "cloudinary://" prefix
    $cleanUrl = str_replace('cloudinary://', '', $cloudUrl);
    
    // Hatiin sa "@" para makuha ang Cloud Name (nasa kanan)
    $parts = explode('@', $cleanUrl);
    
    if (count($parts) === 2) {
        $cloudName = $parts[1]; // dqkzofruk
        
        // Hatiin ang kaliwang parte sa ":" para makuha ang Key at Secret
        $creds = explode(':', $parts[0]);
        if (count($creds) === 2) {
            $apiKey = $creds[0];    // 452544782214523
            $apiSecret = $creds[1]; // Dew-wu6KDw8HNKzO473L5P5tpqo
        }
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
    | Cloud Settings (ITO ANG SOLUSYON SA ERROR MO)
    |--------------------------------------------------------------------------
    | Dito natin ipapasa ang mga nakuha nating detalye sa itaas.
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