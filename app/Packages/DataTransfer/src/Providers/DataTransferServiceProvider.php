<?php

namespace App\Packages\DataTransfer\Providers;

use Illuminate\Support\ServiceProvider;

class DataTransferServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'data-transfer');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/importers.php', 'data_transfer.importers');
        $this->mergeConfigFrom(__DIR__.'/../Config/exporters.php', 'data_transfer.exporters');
    }
}
