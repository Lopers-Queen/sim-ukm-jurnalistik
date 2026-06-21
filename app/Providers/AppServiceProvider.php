<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS in production (Railway reverse proxy)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Use Bootstrap 5 pagination instead of Tailwind
        Paginator::useBootstrapFive();

        // Super Admin bypass — role super_admin melewati SEMUA permission checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
