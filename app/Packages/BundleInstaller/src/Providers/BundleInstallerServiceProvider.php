<?php

namespace App\Packages\BundleInstaller\Providers;

use App\Packages\BundleInstaller\Console\Commands\CreateBundleCommand;
use App\Packages\BundleInstaller\Console\Commands\CreateProBundleCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BundleInstallerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../Resources/views',
            'bundle-installer'
        );
        $this->registerRoutes();
        $this->registerCommands();
    }

    private function registerRoutes(): void
    {
        require base_path('routes/bundle-installer.php');
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateBundleCommand::class,
                CreateProBundleCommand::class,
            ]);
        }
    }
}
