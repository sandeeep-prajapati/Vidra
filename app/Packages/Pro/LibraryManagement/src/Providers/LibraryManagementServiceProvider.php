<?php

namespace App\Packages\Pro\LibraryManagement\Providers;

use App\Services\MenuItem;
use App\Services\MenuService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LibraryManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'library-management');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->registerMenuItems();

        $this->callAfterResolving('migrator', function () {
            $this->registerPermissions();
        });
    }

    public function register(): void
    {
        //
    }

    private function registerMenuItems(): void
    {
        $this->booted(function () {
            if (!$this->app->bound(MenuService::class)) {
                return;
            }

            $menu = $this->app->make(MenuService::class);
            $icon = '<svg class="icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>';

            $menu->addItem('library', new MenuItem('Books',    $icon, 'library.books.index',   'library', 'library.books.*'));
            $menu->addItem('library', new MenuItem('Members',  $icon, 'library.members.index', 'library', 'library.members.*'));
            $menu->addItem('library', new MenuItem('Issues',   $icon, 'library.issues.index',  'library', 'library.issues.*'));
            $menu->addItem('library', new MenuItem('Fines',    $icon, 'library.fines.index',   'library', 'library.fines.*'));
        });
    }

    private function registerPermissions(): void
    {
        if (!Schema::hasTable('permissions') || !Schema::hasTable('roles')) {
            return;
        }

        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $permissions = [
                'view_library-management'        => 'View library pages',
                'create_library-management_item' => 'Add books and members',
                'edit_library-management_item'   => 'Edit items and process returns',
                'delete_library-management_item' => 'Delete books and members',
                'manage_library_fines'           => 'Manage fine payments and waivers',
            ];

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['description' => $description, 'module_name' => 'library']
                );
            }

            $allPerms = array_keys($permissions);

            $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $admin->givePermissionTo(array_filter($allPerms, fn ($p) => !in_array($p, $admin->permissions->pluck('name')->toArray())));

            $librarian = Role::firstOrCreate(['name' => 'librarian', 'guard_name' => 'web']);
            $librarian->givePermissionTo(array_filter($allPerms, fn ($p) => !in_array($p, $librarian->permissions->pluck('name')->toArray())));

            $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
            if (!$teacher->hasPermissionTo('view_library-management')) {
                $teacher->givePermissionTo('view_library-management');
            }
        } catch (\Exception) {
            // Silently skip — permissions will be seeded on next boot once DB is ready
        }
    }
}
