<?php

namespace App\Packages\Pro\DemoBundle\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class DemoController extends BaseController
{
    public function index(): View
    {
        return view('demo-bundle::index', [
            'title' => 'Demo Bundle',
            'message' => 'Welcome to the Demo Bundle!',
        ]);
    }

    public function features(): View
    {
        $features = [
            'Automatic PSR-4 Registration' => 'Bundle packages are automatically registered with the correct namespace',
            'Service Provider Auto-Loading' => 'Service providers are automatically registered in bootstrap/providers.php',
            'View Namespace' => 'Views are namespaced to avoid conflicts (demo-bundle::view-name)',
            'Database Migrations' => 'Migrations are auto-loaded and can be run with php artisan migrate',
            'Route Loading' => 'Routes are automatically loaded from the bundle',
        ];

        return view('demo-bundle::features', [
            'features' => $features,
        ]);
    }
}
