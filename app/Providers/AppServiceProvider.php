<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 👇 ITO ANG FIX: Pwersahang i-inject ang Cloudinary Config
        // Ito ang tatapos sa error na "Undefined array key 'cloud'"
        
        config([
            'cloudinary' => [
                'cloud_url' => env('CLOUDINARY_URL'),
                'cloud' => [
                    'cloud_name' => 'dqkzofruk', 
                    'api_key'    => '452544782214523', // Root API Key
                    'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo', // Root API Secret
                ],
                'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
                'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
                'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),
                'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),
            ]
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Force HTTPS sa Production (Vercel)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}