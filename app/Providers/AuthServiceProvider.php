<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Location;
use App\Policies\AssetPolicy;
use App\Policies\LocationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Asset::class => AssetPolicy::class,
        Location::class => LocationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

// bootstrap/app.php (tambahkan middleware alias)
// Tambahkan di array middleware:
/*
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
*/