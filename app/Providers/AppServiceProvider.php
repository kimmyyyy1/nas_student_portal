<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config; // <--- IMPORTANT IMPORT

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

        // ☢️ NUCLEAR FIX: SAPILITANG CONFIGURATION
        // Ito ay magsi-set ng config sa memory habang tumatakbo ang app.
        // Hindi na ito aasa kung nabasa ba o hindi ang config/cloudinary.php file.
        
        Config::set('cloudinary.cloud_url', 'cloudinary://452544782214523:Dew-wu6KDw8HNKzO473L5P5tpqo@dqkzofruk');
        Config::set('cloudinary.cloud', [
            'cloud_name' => 'dqkzofruk',
            'api_key'    => '452544782214523',
            'api_secret' => 'Dew-wu6KDw8HNKzO473L5P5tpqo',
            'key'        => '452544782214523', // Alias fix
            'secret'     => 'Dew-wu6KDw8HNKzO473L5P5tpqo', // Alias fix
        ]);
        
        // Siguraduhin din natin na ang Filesystem disk ay may tamang settings
        Config::set('filesystems.disks.cloudinary.key', '452544782214523');
        Config::set('filesystems.disks.cloudinary.secret', 'Dew-wu6KDw8HNKzO473L5P5tpqo');
    }
}