<?php

namespace App\Packages\RbacManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RbacManagementSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'students'        => ['view', 'create', 'edit', 'delete'],
            'staff'           => ['view', 'create', 'edit', 'delete'],
            'classes'         => ['view', 'create', 'edit', 'delete'],
            'subjects'        => ['view', 'create', 'edit', 'delete'],
            'attendance'      => ['view', 'create', 'edit', 'delete'],
            'exams'           => ['view', 'create', 'edit', 'delete'],
            'fees'            => ['view', 'create', 'edit', 'delete'],
            'timetable'       => ['view', 'create', 'edit', 'delete'],
            'communication'   => ['view', 'create', 'edit', 'delete'],
            'hostel'          => ['view', 'create', 'edit', 'delete'],
            'transport'       => ['view', 'create', 'edit', 'delete'],
            'reports'         => ['view'],
            'rbac'            => ['view', 'create', 'edit', 'delete'],
        ];

        $allPermissions = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate(
                    ['name' => "{$action}-{$module}", 'guard_name' => 'web'],
                    ['description' => ucfirst($action).' '.ucfirst($module), 'module_name' => $module]
                );
                $allPermissions[] = $permission->name;
            }
        }

        $roles = [
            [
                'name'        => 'super-admin',
                'description' => 'Full access to all modules',
                'permissions' => $allPermissions,
            ],
            [
                'name'        => 'admin',
                'description' => 'Administrative access excluding RBAC management',
                'permissions' => array_filter($allPermissions, fn($p) => !str_starts_with($p, 'delete-rbac')),
            ],
            [
                'name'        => 'teacher',
                'description' => 'Teacher access: attendance, exams, timetable, subjects',
                'permissions' => [
                    'view-students', 'view-attendance', 'create-attendance', 'edit-attendance',
                    'view-exams', 'create-exams', 'edit-exams',
                    'view-timetable', 'view-subjects',
                    'view-communication', 'create-communication',
                ],
            ],
            [
                'name'        => 'accountant',
                'description' => 'Finance and fee management access',
                'permissions' => [
                    'view-fees', 'create-fees', 'edit-fees', 'delete-fees',
                    'view-reports',
                    'view-students',
                ],
            ],
            [
                'name'        => 'student',
                'description' => 'Student portal access',
                'permissions' => [
                    'view-attendance', 'view-exams', 'view-timetable',
                    'view-fees', 'view-communication',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => 'web'],
                ['description' => $roleData['description']]
            );
            $role->syncPermissions($roleData['permissions']);
        }
    }
}
