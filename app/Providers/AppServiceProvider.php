<?php

namespace App\Providers;

use App\Models\MaintenanceRecord;
use App\Models\User;
use App\Policies\MaintenancePolicy;
use Illuminate\Support\Facades\Gate;
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
        // Hubungkan model MaintenanceRecord ke MaintenancePolicy
        Gate::policy(MaintenanceRecord::class, MaintenancePolicy::class);

        // Super Admin memiliki hak akses penuh ke seluruh otorisasi Gate
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
