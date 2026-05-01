<?php

namespace App\Packages\HostelTransportManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HostelTransportManagementServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'hostel-transport');
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->group(__DIR__.'/../Routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__.'/../Routes/api.php');
    }
}
