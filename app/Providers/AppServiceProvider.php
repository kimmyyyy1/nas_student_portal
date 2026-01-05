<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Importante para sa View Composer
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
        // I-share ang variable na $pendingAdmissionsCount sa 'layouts.navigation' view
        View::composer('layouts.navigation', function ($view) {
            $count = 0;
            
            try {
                // Bilangin ang mga may status na 'Pending'
                $count = EnrollmentApplication::where('status', 'Pending')->count();
            } catch (\Exception $e) {
                // Iwas error kapag fresh migration pa lang
                $count = 0;
            }

            $view->with('pendingAdmissionsCount', $count);
        });
    }
}