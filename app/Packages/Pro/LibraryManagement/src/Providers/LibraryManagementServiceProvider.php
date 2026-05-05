<?php

namespace App\Packages\Pro\LibraryManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LibraryManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register permissions
        $this->registerPermissions();

        // Load assets
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'library-management');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }

    private function registerPermissions(): void
    {
        $permissions = [
            'view_library-management',
            'create_library-management_item',
            'edit_library-management_item',
            'delete_library-management_item',
            'manage_library_fines',
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
        $librarian = Role::firstOrCreate(['name' => 'librarian']);

        $admin->syncPermissions($permissions);
        $teacher->syncPermissions(['view_library-management']);
        $librarian->syncPermissions($permissions);
    }
}