<?php

namespace App\Packages\RbacManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\RbacManagement\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPermissionController extends Controller
{
    public function edit(User $user): View
    {
        $user->load('permissions', 'roles');
        $allPermissions = Permission::orderBy('module_name')->orderBy('name')->get()->groupBy('module_name');

        return view('rbac::userPermission.edit', compact('user', 'allPermissions'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        $permissions   = Permission::whereIn('id', $permissionIds)->get();
        $user->syncPermissions($permissions);

        return redirect()->route('user-roles.index')->with('success', 'Direct permissions updated for '.$user->name.'.');
    }
}
