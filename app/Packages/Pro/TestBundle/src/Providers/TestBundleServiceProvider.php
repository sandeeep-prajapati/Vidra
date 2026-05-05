<?php

namespace App\Packages\Pro\TestBundle\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register permissions
        $this->registerPermissions();

        // Load assets
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'test-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }

    private function registerPermissions(): void
    {
        $permissions = [
            'view_test-bundle',
            'create_test-bundle_item',
            'edit_test-bundle_item',
            'delete_test-bundle_item',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Assign to roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);

        $admin->syncPermissions($permissions);
        $teacher->syncPermissions(['view_test-bundle', 'edit_test-bundle_item']);
    }
}