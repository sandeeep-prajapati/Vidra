<?php

namespace App\Packages\ContactBundle\Providers;

use Illuminate\Support\ServiceProvider;

class ContactBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'contact-bundle');

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }
}
