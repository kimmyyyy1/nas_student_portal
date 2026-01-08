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
        //
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