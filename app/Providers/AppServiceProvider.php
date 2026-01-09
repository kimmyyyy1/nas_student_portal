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
        // 👇 NUCLEAR FIX: Dito natin ipinapasok ang Cloudinary Config sa memory ng Laravel.
        // Talo nito ang anumang config file. Siguradong babasahin ito.
        
        config([
            'cloudinary.cloud_url' => env('CLOUDINARY_URL'),
            'cloudinary.cloud' => [
                'cloud_name' => 'dqkzofruk', 
                'api_key'    => '681411283875527', // Ito ang "Untitled" key mo
                'api_secret' => 'Q6SMPHbhLkJaKtzGZ7atZmXRwGE', // 👈 PAKI-PASTE DITO YUNG SECRET!
            ],
            'cloudinary.notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
            'cloudinary.upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
            'cloudinary.upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),
            'cloudinary.upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix para sa "Key too long" error sa ibang database versions
        Schema::defaultStringLength(191);

        // LOGIC:
        // Kapag nasa Vercel (Production), pilitin mag-HTTPS para secure at gumana ang CSS.
        // Kapag nasa Local, huwag pilitin para hindi mag-error ang 127.0.0.1.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}