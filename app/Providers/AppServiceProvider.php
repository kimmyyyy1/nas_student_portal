<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Iwanang malinis/bakante
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Restrict access to Log Viewer to IT Admin and Super Admin only
        Gate::define('viewLogViewer', function ($user = null) {
            return $user && in_array($user->role, ['IT Admin', 'Super Admin']);
        });
    }
}