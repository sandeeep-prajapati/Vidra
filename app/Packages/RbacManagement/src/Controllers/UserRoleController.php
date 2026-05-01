<?php

namespace App\Packages\RbacManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\RbacManagement\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $items = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('rbac::userRole.index', compact('items'));
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();

        return view('rbac::userRole.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'roles'   => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roleIds = $validated['roles'] ?? [];
        $roles   = Role::whereIn('id', $roleIds)->get();
        $user->syncRoles($roles);

        return redirect()->route('user-roles.index')->with('success', 'Roles updated for '.$user->name.'.');
    }
}
