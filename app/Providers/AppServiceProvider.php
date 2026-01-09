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
     */
    public function register(): void
    {
        //
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

        // ☢️ NUCLEAR FIX: RUNTIME INJECTION ☢️
        // Pinipilit natin ang settings sa memory ng Laravel.
        // Kahit hindi niya mabasa ang file, nandito ang backup.

        // 1. Force set the main Cloudinary config
        Config::set('cloudinary.cloud_url', 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk');
        Config::set('cloudinary.cloud', [
            'cloud_name' => 'dqkzofruk',
            'api_key'    => '452544782214523',
            'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            'key'        => '452544782214523', 
            'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
        ]);

        // 2. Force set the Filesystem config (Flat structure)
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
}