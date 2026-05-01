<?php

namespace App\Packages\CorePackage\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class CorePackageServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'core-package');

        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $blade->anonymousComponentPath(
                __DIR__.'/../Resources/views/components',
                'core-package'
            );
        });
    }
}
