<?php

namespace App\Packages\StudentManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class StudentManagementServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register package services
    }

    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../Views', 'student-management');

        // Register routes
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        // Web routes
        Route::middleware(['web', 'auth'])
            ->namespace('App\Packages\StudentManagement\Controllers')
            ->group(__DIR__.'/../Routes/web.php');

        // API routes
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\Packages\StudentManagement\Controllers')
            ->group(__DIR__.'/../Routes/api.php');
    }
}
