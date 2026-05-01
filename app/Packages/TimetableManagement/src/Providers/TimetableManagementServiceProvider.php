<?php

namespace App\Packages\TimetableManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TimetableManagementServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'timetable');
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->namespace("App\Packages\TimetableManagement\Controllers")
            ->group(__DIR__.'/../Routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace("App\Packages\TimetableManagement\Controllers")
            ->group(__DIR__.'/../Routes/api.php');
    }
}
