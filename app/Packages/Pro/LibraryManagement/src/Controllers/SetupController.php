<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
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
            'migrations' => $this->checkMigrationsRun(),
            'permissions' => $this->checkPermissionsExist(),
            'roles' => $this->checkRolesExist(),
            'categories' => $this->checkCategoriesExist(),
        ];

        return view('library-management::setup.index', compact('status'));
    }

    public function runSetup(Request $request): \Illuminate\Http\JsonResponse
    {
        $command = $request->input('command', 'all');

        $result = match ($command) {
            'migrate'     => $this->runMigrations(),
            'permissions' => $this->setupPermissions(),
            'roles'       => $this->setupRoles(),
            'seed-data'   => $this->seedInitialData(),
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
                'view_library-management' => 'View library pages',
                'create_library-management_item' => 'Add books and members',
                'edit_library-management_item' => 'Edit items and process returns',
                'delete_library-management_item' => 'Delete books and members',
                'manage_library_fines' => 'Manage fine payments and waivers',
            ];

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['description' => $description]
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
            $permissionNames = [
                'view_library-management',
                'create_library-management_item',
                'edit_library-management_item',
                'delete_library-management_item',
                'manage_library_fines',
            ];

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $permissions = Permission::whereIn('name', $permissionNames)->get();

            $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $admin->givePermissionTo($permissions->filter(fn ($p) => !$admin->hasPermissionTo($p->name)));

            $librarian = Role::firstOrCreate(['name' => 'librarian', 'guard_name' => 'web']);
            $librarian->givePermissionTo($permissions->filter(fn ($p) => !$librarian->hasPermissionTo($p->name)));

            $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
            $viewPerm = Permission::where('name', 'view_library-management')->first();
            if ($viewPerm && !$teacher->hasPermissionTo('view_library-management')) {
                $teacher->givePermissionTo($viewPerm);
            }

            // Grant all permissions to the currently logged-in user's roles
            if (auth()->check()) {
                $handledRoles = ['admin', 'librarian', 'teacher'];
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

    private function seedInitialData(): array
    {
        try {
            $categories = [
                ['name' => 'Science', 'description' => 'Science and Physics books'],
                ['name' => 'Mathematics', 'description' => 'Mathematics and Geometry books'],
                ['name' => 'Literature', 'description' => 'Literature and Languages books'],
                ['name' => 'History', 'description' => 'History and Geography books'],
                ['name' => 'Technology', 'description' => 'Computer and Technology books'],
                ['name' => 'General', 'description' => 'General knowledge books'],
            ];

            foreach ($categories as $category) {
                LibraryCategory::firstOrCreate(
                    ['name' => $category['name']],
                    ['description' => $category['description']]
                );
            }

            return ['success' => true, 'message' => 'Initial data seeded successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Data seeding failed: ' . $e->getMessage()];
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
        $results['roles'] = $this->setupRoles();
        $results['categories'] = $this->seedInitialData();

        return [
            'success' => true,
            'message' => 'All setup completed successfully!',
            'results' => $results
        ];
    }

    private function checkMigrationsRun(): bool
    {
        return Schema::hasTable('library_books');
    }

    private function checkPermissionsExist(): bool
    {
        return Permission::where('name', 'like', '%library%')->count() >= 5;
    }

    private function checkRolesExist(): bool
    {
        return Role::whereIn('name', ['librarian'])->exists();
    }

    private function checkCategoriesExist(): bool
    {
        return Schema::hasTable('library_categories') && LibraryCategory::count() > 0;
    }
}
