<?php

namespace App\Packages\AttendanceManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AttendanceManagementServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'attendance-management');
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->namespace("App\Packages\AttendanceManagement\Controllers")
            ->group(__DIR__.'/../Routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace("App\Packages\AttendanceManagement\Controllers")
            ->group(__DIR__.'/../Routes/api.php');
    }
}
