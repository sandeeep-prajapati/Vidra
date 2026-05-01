<?php

namespace App\Packages\RbacManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\RbacManagement\Models\Permission;
use App\Packages\RbacManagement\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Role::withCount('permissions');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $items = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('rbac::role.index', compact('items'));
    }

    public function create(): View
    {
        return view('rbac::role.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        Role::create(['name' => $validated['name'], 'guard_name' => 'web', 'description' => $validated['description'] ?? null]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role): View
    {
        $role->load('permissions');
        $allPermissions = Permission::orderBy('module_name')->orderBy('name')->get()->groupBy('module_name');

        return view('rbac::role.show', compact('role', 'allPermissions'));
    }

    public function edit(Role $role): View
    {
        return view('rbac::role.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name,'.$role->id,
            'description' => 'nullable|string',
        ]);

        $role->update(['name' => $validated['name'], 'description' => $validated['description'] ?? null]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }

    public function syncPermissions(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        $permissions   = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.show', $role)->with('success', 'Permissions updated successfully.');
    }
}
