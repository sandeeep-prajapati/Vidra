<?php

namespace App\Packages\RbacManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\RbacManagement\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Permission::query();

        if ($request->filled('module_name')) {
            $query->where('module_name', $request->module_name);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $items   = $query->orderBy('module_name')->orderBy('name')->paginate(20)->withQueryString();
        $modules = Permission::distinct()->orderBy('module_name')->pluck('module_name')->filter();

        return view('rbac::permission.index', compact('items', 'modules'));
    }

    public function create(): View
    {
        $modules = Permission::distinct()->orderBy('module_name')->pluck('module_name')->filter();

        return view('rbac::permission.create', compact('modules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:permissions,name',
            'description' => 'nullable|string',
            'module_name' => 'nullable|string|max:100',
        ]);

        Permission::create([
            'name'        => $validated['name'],
            'guard_name'  => 'web',
            'description' => $validated['description'] ?? null,
            'module_name' => $validated['module_name'] ?? null,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission): View
    {
        $permission->load('roles');

        return view('rbac::permission.show', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        $modules = Permission::distinct()->orderBy('module_name')->pluck('module_name')->filter();

        return view('rbac::permission.edit', compact('permission', 'modules'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:permissions,name,'.$permission->id,
            'description' => 'nullable|string',
            'module_name' => 'nullable|string|max:100',
        ]);

        $permission->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'module_name' => $validated['module_name'] ?? null,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted.');
    }
}
