<?php

namespace App\Packages\Pro\AlumniManagement\Providers;

use App\Services\MenuItem;
use App\Services\MenuService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AlumniManagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'alumni-management');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->registerMenuItems();

        $this->callAfterResolving('migrator', function () {
            $this->registerPermissions();
        });
    }

    private function registerMenuItems(): void
    {
        $this->booted(function () {
            if (!$this->app->bound(MenuService::class)) {
                return;
            }

            $menu = $this->app->make(MenuService::class);
            $icon = '<svg class="icon" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>';

            $menu->addItem('alumni', new MenuItem('Directory',  $icon, 'alumni.directory',       'alumni', 'alumni.directory*'));
            $menu->addItem('alumni', new MenuItem('Events',     $icon, 'alumni.events.index',    'alumni', 'alumni.events.*'));
            $menu->addItem('alumni', new MenuItem('Donations',  $icon, 'alumni.donations.index', 'alumni', 'alumni.donations.*'));
            $menu->addItem('alumni', new MenuItem('Mentorship', $icon, 'alumni.mentorship.index','alumni', 'alumni.mentorship.*'));
        });
    }

    public function register(): void
    {
        //
    }

    private function registerPermissions(): void
    {
        if (!Schema::hasTable('permissions') || !Schema::hasTable('roles')) {
            return;
        }

        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $permissions = [
                'view_alumni-management'        => 'View alumni pages and directory',
                'create_alumni-management_item' => 'Add alumni profiles and events',
                'edit_alumni-management_item'   => 'Edit alumni data and manage registrations',
                'delete_alumni-management_item' => 'Delete alumni profiles',
                'manage_alumni_donations'        => 'Record and verify donations',
                'manage_alumni_mentorship'       => 'Create and manage mentorship pairings',
            ];

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['description' => $description, 'module_name' => 'alumni']
                );
            }

            $allPerms = array_keys($permissions);

            $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $existing = $admin->permissions->pluck('name')->toArray();
            $admin->givePermissionTo(array_filter($allPerms, fn ($p) => !in_array($p, $existing)));

            $coordinator = Role::firstOrCreate(['name' => 'alumni_coordinator', 'guard_name' => 'web']);
            $coordinator->syncPermissions($allPerms);

            $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
            if (!$teacher->hasPermissionTo('view_alumni-management')) {
                $teacher->givePermissionTo('view_alumni-management');
            }
        } catch (\Exception) {
            // Silently skip — permissions will be seeded on next boot once DB is ready
        }
    }
}
