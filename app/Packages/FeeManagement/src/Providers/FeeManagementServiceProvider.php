<?php

namespace App\Packages\FeeManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FeeManagementServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'fee-management');
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->namespace("App\Packages\FeeManagement\Controllers")
            ->group(__DIR__.'/../Routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace("App\Packages\FeeManagement\Controllers")
            ->group(__DIR__.'/../Routes/api.php');
    }
}
