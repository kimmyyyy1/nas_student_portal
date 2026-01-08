<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
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
        // VERCEL CSS/ASSETS FIX (IMPORTANT)
        // ==========================================
        // Pilitin ang HTTPS para gumana ang styles sa Vercel.
        // Paalala: Kung nag-e-error ito sa Local XAMPP, i-comment out muna (lagyan ng // sa unahan).
        URL::forceScheme('https');

        // ==========================================
        // SIDEBAR BADGE COUNTER (Shared Logic)
        // ==========================================
        View::composer('layouts.navigation', function ($view) {
            $count = 0;
            
            try {
                // Check kung existing ang table bago mag-query para iwas error sa migration
                if (Schema::hasTable('enrollment_applications')) {
                     $count = EnrollmentApplication::where('status', 'Pending')->count();
                }
            } catch (\Exception $e) {
                // Silent fail kapag may database connection error
                $count = 0;
            }

            $view->with('pendingAdmissionsCount', $count);
        });
    }
}