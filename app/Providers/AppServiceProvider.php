<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config; // 👈 IMPORTANT IMPORT

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * DITO NATIN ILALAGAY ANG CONFIG PARA UNAHAN ANG CLOUDINARY.
     */
    public function register(): void
    {
        // 🚀 SUPER NUCLEAR FIX (Moved to register)
        // Set config BEFORE the package boots up.
        
        // 1. Force Cloudinary Main Config (Para sa Error: "Undefined array key cloud")
        Config::set('cloudinary.cloud_url', 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk');
        Config::set('cloudinary.cloud', [
            'cloud_name' => 'dqkzofruk',
            'api_key'    => '452544782214523',
            'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            'key'        => '452544782214523', 
            'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
        ]);

        // 2. Force Filesystem Config (Para sa Error: "TypeError")
        Config::set('filesystems.disks.cloudinary', [
            'driver'     => 'cloudinary',
            'cloud_name' => 'dqkzofruk',
            'api_key'    => '452544782214523',
            'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            'key'        => '452544782214523',
            'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            'cloud_url'  => 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk',
            'throw'      => false,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}