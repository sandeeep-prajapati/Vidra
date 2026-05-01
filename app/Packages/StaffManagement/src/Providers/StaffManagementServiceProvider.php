<?php

namespace App\Packages\StaffManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class StaffManagementServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'staff-management');
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->namespace('App\Packages\StaffManagement\Controllers')
            ->group(__DIR__.'/../Routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\Packages\StaffManagement\Controllers')
            ->group(__DIR__.'/../Routes/api.php');
    }
}
