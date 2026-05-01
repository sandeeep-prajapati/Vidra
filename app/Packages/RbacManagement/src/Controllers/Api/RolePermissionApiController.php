<?php

namespace App\Packages\RbacManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\RbacManagement\Models\Permission;
use App\Packages\RbacManagement\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group RBAC Management
 */
class RolePermissionApiController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        return response()->json($roles);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json($role->load('permissions'));
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])->get();
        $role->syncPermissions($permissions);

        return response()->json($role->load('permissions'));
    }
}
