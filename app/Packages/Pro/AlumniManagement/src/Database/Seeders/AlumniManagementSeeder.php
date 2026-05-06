<?php

namespace App\Packages\Pro\AlumniManagement\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AlumniManagementSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create all alumni permissions
        $permissions = [
            ['name' => 'view_alumni-management',        'description' => 'View alumni pages and directory',          'module_name' => 'alumni'],
            ['name' => 'create_alumni-management_item', 'description' => 'Add alumni profiles and events',            'module_name' => 'alumni'],
            ['name' => 'edit_alumni-management_item',   'description' => 'Edit alumni data and manage registrations', 'module_name' => 'alumni'],
            ['name' => 'delete_alumni-management_item', 'description' => 'Delete alumni profiles',                    'module_name' => 'alumni'],
            ['name' => 'manage_alumni_donations',        'description' => 'Record and verify alumni donations',        'module_name' => 'alumni'],
            ['name' => 'manage_alumni_mentorship',       'description' => 'Create and manage mentorship pairings',     'module_name' => 'alumni'],
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
        $admin->givePermissionTo(
            Permission::whereIn('name', $permissionNames)
                ->whereDoesntHave('roles', fn ($q) => $q->where('roles.id', $admin->id))
                ->get()
        );

        $coordinator = Role::firstOrCreate(
            ['name' => 'alumni_coordinator', 'guard_name' => 'web'],
            ['description' => 'Manages alumni community and events']
        );
        $coordinator->syncPermissions($permissionNames);

        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web'], ['description' => 'Teaching staff']);
        if (!$teacher->hasPermissionTo('view_alumni-management')) {
            $teacher->givePermissionTo('view_alumni-management');
        }

        // 3. Auto-assign admin role to the first user
        $user = User::first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->command->info("Assigned 'admin' role to user: {$user->email}");
        } elseif ($user) {
            $this->command->info("User {$user->email} already has the 'admin' role.");
        } else {
            $this->command->warn('No users found — create a user then run php artisan db:seed --class=AlumniManagementSeeder');
        }

        $this->command->info('Alumni permissions seeded successfully.');
    }
}
