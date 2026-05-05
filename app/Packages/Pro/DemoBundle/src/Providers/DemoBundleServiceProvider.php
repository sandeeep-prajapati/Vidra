<?php

namespace App\Packages\Pro\DemoBundle\Providers;

use Illuminate\Support\ServiceProvider;

class DemoBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'demo-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->publishes([
            __DIR__.'/../Assets' => public_path('vendor/demo-bundle'),
        ], 'demo-bundle-assets');
    }

    public function register(): void
    {
        //
    }
}
