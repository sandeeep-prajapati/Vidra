<?php

namespace App\Packages\Pro\LibraryManagement\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LibraryManagementSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create all library permissions
        $permissions = [
            ['name' => 'view_library-management',        'description' => 'View library pages',                  'module_name' => 'library'],
            ['name' => 'create_library-management_item', 'description' => 'Add books, members and issue books',   'module_name' => 'library'],
            ['name' => 'edit_library-management_item',   'description' => 'Edit items and process book returns',  'module_name' => 'library'],
            ['name' => 'delete_library-management_item', 'description' => 'Delete books and members',             'module_name' => 'library'],
            ['name' => 'manage_library_fines',           'description' => 'Record payments and waive fines',      'module_name' => 'library'],
        ];

        $permissionNames = [];
        foreach ($permissions as $data) {
            Permission::firstOrCreate(
                ['name' => $data['name'], 'guard_name' => 'web'],
                ['description' => $data['description'], 'module_name' => $data['module_name']]
            );
            $permissionNames[] = $data['name'];
        }

        // 2. Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'], ['description' => 'Administrator']);
        $admin->givePermissionTo(Permission::whereIn('name', $permissionNames)->whereDoesntHave('roles', fn ($q) => $q->where('roles.id', $admin->id))->get());

        $librarian = Role::firstOrCreate(['name' => 'librarian', 'guard_name' => 'web'], ['description' => 'Manages the library day-to-day']);
        $librarian->syncPermissions($permissionNames);

        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web'], ['description' => 'Teaching staff']);
        if (!$teacher->hasPermissionTo('view_library-management')) {
            $teacher->givePermissionTo('view_library-management');
        }

        // 3. Auto-assign admin role to the first user (logged-in / seeded user)
        $user = User::first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->command->info("Assigned 'admin' role to user: {$user->email}");
        } elseif ($user) {
            $this->command->info("User {$user->email} already has the 'admin' role.");
        } else {
            $this->command->warn('No users found — create a user and then run php artisan db:seed --class=LibraryManagementSeeder');
        }

        $this->command->info('Library permissions seeded successfully.');
    }
}
