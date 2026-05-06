<?php

namespace App\Packages\Pro\AlumniManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupController extends BaseController
{
    public function showSetup(): View
    {
        $status = [
            'migrations'  => $this->checkMigrationsRun(),
            'permissions' => $this->checkPermissionsExist(),
            'roles'       => $this->checkRolesExist(),
        ];

        return view('alumni-management::setup.index', compact('status'));
    }

    public function runSetup(Request $request): \Illuminate\Http\JsonResponse
    {
        $command = $request->input('command', 'all');

        $result = match ($command) {
            'migrate'     => $this->runMigrations(),
            'permissions' => $this->setupPermissions(),
            'roles'       => $this->setupRoles(),
            'all'         => $this->runAllSetup(),
            default       => ['success' => false, 'message' => 'Unknown command'],
        };

        return response()->json($result);
    }

    private function runMigrations(): array
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return ['success' => true, 'message' => 'Migrations completed successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Migration failed: ' . $e->getMessage()];
        }
    }

    private function setupPermissions(): array
    {
        try {
            $permissions = [
                'view_alumni-management'        => 'View alumni pages and directory',
                'create_alumni-management_item' => 'Add alumni profiles and events',
                'edit_alumni-management_item'   => 'Edit alumni data and manage registrations',
                'delete_alumni-management_item' => 'Delete alumni profiles',
                'manage_alumni_donations'        => 'Record and verify alumni donations',
                'manage_alumni_mentorship'       => 'Create and manage mentorship pairings',
            ];

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['description' => $description, 'module_name' => 'alumni']
                );
            }

            return ['success' => true, 'message' => 'Permissions created successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Permission setup failed: ' . $e->getMessage()];
        }
    }

    private function setupRoles(): array
    {
        try {
            $allPerms = [
                'view_alumni-management',
                'create_alumni-management_item',
                'edit_alumni-management_item',
                'delete_alumni-management_item',
                'manage_alumni_donations',
                'manage_alumni_mentorship',
            ];

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $permissions = Permission::whereIn('name', $allPerms)->get();

            $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $admin->givePermissionTo($permissions->filter(fn ($p) => !$admin->hasPermissionTo($p->name)));

            $coordinator = Role::firstOrCreate(['name' => 'alumni_coordinator', 'guard_name' => 'web']);
            $coordinator->givePermissionTo($permissions->filter(fn ($p) => !$coordinator->hasPermissionTo($p->name)));

            $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
            $viewPerm = Permission::where('name', 'view_alumni-management')->first();
            if ($viewPerm && !$teacher->hasPermissionTo('view_alumni-management')) {
                $teacher->givePermissionTo($viewPerm);
            }

            // Grant all permissions to the currently logged-in user's roles
            if (auth()->check()) {
                $handledRoles = ['admin', 'alumni_coordinator', 'teacher'];
                foreach (auth()->user()->roles as $role) {
                    if (!in_array($role->name, $handledRoles)) {
                        $role->givePermissionTo($permissions->filter(fn ($p) => !$role->hasPermissionTo($p->name)));
                    }
                }
            }

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return ['success' => true, 'message' => 'Roles configured successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Role setup failed: ' . $e->getMessage()];
        }
    }

    private function runAllSetup(): array
    {
        $results = [];

        $results['migrations'] = $this->runMigrations();
        if (!$results['migrations']['success']) {
            return ['success' => false, 'message' => 'Setup halted: ' . $results['migrations']['message']];
        }

        $results['permissions'] = $this->setupPermissions();
        $results['roles']       = $this->setupRoles();

        return [
            'success' => true,
            'message' => 'All setup completed successfully!',
            'results' => $results,
        ];
    }

    private function checkMigrationsRun(): bool
    {
        return Schema::hasTable('alumni_profiles');
    }

    private function checkPermissionsExist(): bool
    {
        return Permission::where('name', 'like', '%alumni%')->count() >= 6;
    }

    private function checkRolesExist(): bool
    {
        return Role::where('name', 'alumni_coordinator')->exists();
    }
}
