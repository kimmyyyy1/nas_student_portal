<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL; // <--- Importante!
use Illuminate\Support\Facades\Schema;
use App\Models\EnrollmentApplication;

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
        // ==========================================
        // 1. FORCE HTTPS (FIX FOR BROKEN STYLES)
        // ==========================================
        // Ito ang mag-aayos ng "text-only" na itsura sa Vercel.
        // Pinipilit natin ang Laravel na gamitin ang 'https://' sa lahat ng links.
        URL::forceScheme('https');

        // ==========================================
        // 2. SIDEBAR BADGE LOGIC
        // ==========================================
        View::composer('layouts.navigation', function ($view) {
            $count = 0;
            try {
                if (Schema::hasTable('enrollment_applications')) {
                     $count = EnrollmentApplication::where('status', 'Pending')->count();
                }
            } catch (\Exception $e) {
                $count = 0;
            }
            $view->with('pendingAdmissionsCount', $count);
        });
    }
}