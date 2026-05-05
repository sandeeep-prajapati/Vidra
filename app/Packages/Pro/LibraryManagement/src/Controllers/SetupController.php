<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupController extends BaseController
{
    /**
     * Show setup page
     */
    public function showSetup(): View
    {
        $status = $this->getSetupStatus();
        return view('library-management::setup.index', $status);
    }

    /**
     * Run setup/installation commands
     */
    public function runSetup(Request $request): JsonResponse
    {
        try {
            $command = $request->input('command');

            $result = match ($command) {
                'migrate' => $this->runMigrations(),
                'permissions' => $this->setupPermissions(),
                'roles' => $this->setupRoles(),
                'seed-data' => $this->seedInitialData(),
                'all' => $this->runAllSetup(),
                default => ['success' => false, 'message' => 'Unknown command']
            };

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Run all migrations
     */
    private function runMigrations(): array
    {
        try {
            Artisan::call('migrate', [
                '--path' => 'app/Packages/Pro/LibraryManagement/src/Database/migrations',
            ]);

            return [
                'success' => true,
                'message' => 'Migrations completed successfully',
                'step' => 'migrate',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Setup permissions
     */
    private function setupPermissions(): array
    {
        try {
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

            return [
                'success' => true,
                'message' => count($permissions) . ' permissions created/updated',
                'step' => 'permissions',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Permission setup failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Setup roles
     */
    private function setupRoles(): array
    {
        try {
            $admin = Role::firstOrCreate(['name' => 'admin']);
            $librarian = Role::firstOrCreate(['name' => 'librarian']);
            $teacher = Role::firstOrCreate(['name' => 'teacher']);

            $adminPermissions = [
                'view_library-management',
                'create_library-management_item',
                'edit_library-management_item',
                'delete_library-management_item',
                'manage_library_fines',
            ];

            $librarianPermissions = [
                'view_library-management',
                'create_library-management_item',
                'edit_library-management_item',
                'delete_library-management_item',
                'manage_library_fines',
            ];

            $teacherPermissions = [
                'view_library-management',
            ];

            $admin->syncPermissions($adminPermissions);
            $librarian->syncPermissions($librarianPermissions);
            $teacher->syncPermissions($teacherPermissions);

            return [
                'success' => true,
                'message' => 'Roles and permissions assigned',
                'step' => 'roles',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Role setup failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Seed initial library data
     */
    private function seedInitialData(): array
    {
        try {
            // Create default categories
            $categories = [
                ['name' => 'Fiction', 'description' => 'Fictional stories and novels'],
                ['name' => 'Science', 'description' => 'Science and technology books'],
                ['name' => 'Mathematics', 'description' => 'Math textbooks and resources'],
                ['name' => 'History', 'description' => 'History and social studies'],
                ['name' => 'Reference', 'description' => 'Dictionaries and reference materials'],
                ['name' => 'Children', 'description' => 'Children and young adult books'],
            ];

            $categoryModel = 'App\Packages\Pro\LibraryManagement\Models\LibraryCategory';

            foreach ($categories as $category) {
                if (!class_exists($categoryModel)) {
                    throw new \Exception('LibraryCategory model not found');
                }

                $model = app($categoryModel);
                $model::firstOrCreate(
                    ['name' => $category['name']],
                    ['description' => $category['description']]
                );
            }

            return [
                'success' => true,
                'message' => count($categories) . ' categories seeded',
                'step' => 'seed-data',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Data seeding failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Run all setup steps
     */
    private function runAllSetup(): array
    {
        $results = [];

        // Run migrations
        $result = $this->runMigrations();
        $results[] = $result;
        if (!$result['success']) {
            return ['success' => false, 'steps' => $results];
        }

        // Setup permissions
        $result = $this->setupPermissions();
        $results[] = $result;

        // Setup roles
        $result = $this->setupRoles();
        $results[] = $result;

        // Seed data
        $result = $this->seedInitialData();
        $results[] = $result;

        return [
            'success' => true,
            'message' => 'All setup steps completed',
            'steps' => $results,
        ];
    }

    /**
     * Get current setup status
     */
    private function getSetupStatus(): array
    {
        return [
            'migrations' => $this->checkMigrationsRun(),
            'permissions' => $this->checkPermissionsExist(),
            'roles' => $this->checkRolesExist(),
            'categories' => $this->checkCategoriesExist(),
        ];
    }

    /**
     * Check if migrations have run
     */
    private function checkMigrationsRun(): bool
    {
        return DB::table('information_schema.tables')
            ->where('table_schema', env('DB_DATABASE'))
            ->where('table_name', 'library_books')
            ->exists();
    }

    /**
     * Check if permissions exist
     */
    private function checkPermissionsExist(): bool
    {
        return Permission::where('name', 'view_library-management')->exists();
    }

    /**
     * Check if roles exist
     */
    private function checkRolesExist(): bool
    {
        return Role::where('name', 'librarian')->exists();
    }

    /**
     * Check if categories have been seeded
     */
    private function checkCategoriesExist(): bool
    {
        try {
            $categoryModel = 'App\Packages\Pro\LibraryManagement\Models\LibraryCategory';
            if (class_exists($categoryModel)) {
                return app($categoryModel)::count() > 0;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
