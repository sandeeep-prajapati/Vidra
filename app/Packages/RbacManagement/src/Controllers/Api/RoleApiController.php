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
class RoleApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Role::withCount('permissions')->orderBy('name')->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web', 'description' => $validated['description'] ?? null]);

        return response()->json($role->load('permissions'), 201);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json($role->load('permissions'));
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name,'.$role->id,
            'description' => 'nullable|string',
        ]);

        $role->update(['name' => $validated['name'], 'description' => $validated['description'] ?? null]);

        return response()->json($role->fresh());
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json(['message' => 'Role deleted.']);
    }

    public function syncPermissions(Request $request, Role $role): JsonResponse
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
