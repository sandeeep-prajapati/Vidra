<?php

namespace App\Packages\Pro\AIBasedReporting\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AIBasedReportingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register permissions
        $this->registerPermissions();

        // Load assets
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'a-i-based-reporting');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }

    private function registerPermissions(): void
    {
        $permissions = [
            'view_a-i-based-reporting',
            'create_a-i-based-reporting_item',
            'edit_a-i-based-reporting_item',
            'delete_a-i-based-reporting_item',
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
        $teacher->syncPermissions(['view_a-i-based-reporting', 'edit_a-i-based-reporting_item']);
    }
}