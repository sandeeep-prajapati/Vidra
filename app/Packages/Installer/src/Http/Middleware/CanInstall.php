<?php

namespace App\Packages\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Packages\Installer\Helpers\DatabaseManager;

class CanInstall
{
    public function handle(Request $request, Closure $next): mixed
    {
        $onInstallRoute = str_contains($request->getPathInfo(), '/install');

        if ($onInstallRoute) {
            if ($this->isAlreadyInstalled() && ! $request->expectsJson()) {
                return redirect()->route('dashboard');
            }
        } else {
            if (! $this->isAlreadyInstalled()) {
                return redirect()->route('installer.index');
            }
        }

        return $next($request);
    }

    public function isAlreadyInstalled(): bool
    {
        if (file_exists(storage_path('installed'))) {
            return true;
        }

        return app(DatabaseManager::class)->isInstalled();
    }
}
