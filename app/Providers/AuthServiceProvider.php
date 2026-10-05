<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\MaintenanceRecord;
use App\Policies\AssetPolicy;
use App\Policies\LocationPolicy;
use App\Policies\MaintenancePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; 

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Asset::class => AssetPolicy::class,
        Location::class => LocationPolicy::class,
        MaintenanceRecord::class => MaintenancePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
