<?php

namespace App\Packages\Webhook\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WebhookServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'webhook');
        $this->loadTranslationsFrom(__DIR__.'/../Lang', 'webhook');

        $this->app->register(EventServiceProvider::class);

        Route::middleware('web')
            ->group(__DIR__.'/../Routes/web.php');
    }
}
