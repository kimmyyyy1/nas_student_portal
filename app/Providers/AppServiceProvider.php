<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 🚀 SECURE NUCLEAR FIX
        // Kinukuha ang values sa Environment Variables (Vercel Settings / .env)
        
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        $cloudUrl = env('CLOUDINARY_URL');

        // Kung walang nakaset na URL, bubuuin natin manually
        if (!$cloudUrl && $cloudName && $apiKey && $apiSecret) {
            $cloudUrl = "cloudinary://{$apiKey}:{$apiSecret}@{$cloudName}";
        }

        // 1. Force Cloudinary Main Config
        if ($cloudUrl) {
            Config::set('cloudinary.cloud_url', $cloudUrl);
            Config::set('cloudinary.cloud', [
                'cloud_name' => $cloudName,
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
                'key'        => $apiKey, 
                'secret'     => $apiSecret,
            ]);
        }

        // 2. Force Filesystem Config
        if ($cloudName) {
            Config::set('filesystems.disks.cloudinary', [
                'driver'     => 'cloudinary',
                'cloud_name' => $cloudName,
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
                'key'        => $apiKey,
                'secret'     => $apiSecret,
                'cloud_url'  => $cloudUrl,
                'throw'      => false,
            ]);
        }
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