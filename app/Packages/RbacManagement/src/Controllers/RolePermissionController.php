<?php

namespace App\Packages\RbacManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\RbacManagement\Models\Permission;
use App\Packages\RbacManagement\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('rbac::rolePermission.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $allPermissions = Permission::orderBy('module_name')->orderBy('name')->get()->groupBy('module_name');

        return view('rbac::rolePermission.edit', compact('role', 'allPermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        $permissions   = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('role-permissions.index')->with('success', 'Permissions for role "'.$role->name.'" updated.');
    }
}
