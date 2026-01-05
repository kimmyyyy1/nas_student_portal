<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Importante para sa View Composer
use Illuminate\Support\Facades\URL;  // <--- Idinagdag natin ito para sa HTTPS fix
use App\Models\EnrollmentApplication; // Importante para sa Database Query

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
        // --- START NG FIX SA VERCEL STYLING ---
        // Kung nasa production (live site), pilitin na gumamit ng HTTPS
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        // --- END NG FIX ---


        // I-share ang variable na $pendingAdmissionsCount sa 'layouts.navigation' view
        View::composer('layouts.navigation', function ($view) {
            $count = 0;
            
            try {
                // Bilangin ang mga may status na 'Pending'
                // Check muna kung existing ang table para di mag-error sa fresh migration
                if (\Schema::hasTable('enrollment_applications')) {
                     $count = EnrollmentApplication::where('status', 'Pending')->count();
                }
            } catch (\Exception $e) {
                // Iwas error kapag may problema sa DB connection o migration
                $count = 0;
            }

            $view->with('pendingAdmissionsCount', $count);
        });
    }
}