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
        // Jika sedang impersonate, bypass dinonaktifkan agar terlihat seperti role tersebut
        Gate::before(function ($user, $ability) {
            // Check if impersonation session has expired (2-hour timeout)
            if (session()->has('impersonating_role') && session()->has('impersonation_started_at')) {
                $startedAt = session('impersonation_started_at');
                $maxMinutes = 120; // 2 hours
                if ($startedAt && (now()->timestamp - $startedAt) > ($maxMinutes * 60)) {
                    session()->forget(['impersonating_role', 'impersonation_started_at']);
                    // Don't return null here — let it fall through to normal super_admin check
                } else {
                    return null; // Impersonation active: normal permission check (no bypass)
                }
            }

            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
