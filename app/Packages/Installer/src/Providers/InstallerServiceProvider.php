<?php

namespace App\Packages\Installer\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use App\Packages\Installer\Http\Middleware\CanInstall;
use App\Packages\Installer\Http\Middleware\Locale;

class InstallerServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $router->middlewareGroup('install', [CanInstall::class]);
        $router->aliasMiddleware('installer_locale', Locale::class);

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'installer');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'installer');
    }

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Packages\Installer\Console\Commands\InstallSchoolApp::class,
            ]);
        }
    }
}
