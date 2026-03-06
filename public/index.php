<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// 🚀 CPANEL/SERVER FIX: Ensure storage path is writable
$storagePath = __DIR__.'/../storage';
if (!is_writable($storagePath)) {
    // If main storage is not writable, fallback to /tmp if necessary
    // This is often needed in shared hosting or serverless
    $tmpStorage = '/tmp/nas_storage';
    if (!is_dir($tmpStorage)) {
        mkdir($tmpStorage, 0777, true);
        mkdir($tmpStorage . '/framework/cache/data', 0777, true);
        mkdir($tmpStorage . '/framework/views', 0777, true);
        mkdir($tmpStorage . '/framework/sessions', 0777, true);
        mkdir($tmpStorage . '/app', 0777, true);
        mkdir($tmpStorage . '/logs', 0777, true);
    }
    $app->useStoragePath($tmpStorage);
}

$app->handleRequest(Request::capture());
