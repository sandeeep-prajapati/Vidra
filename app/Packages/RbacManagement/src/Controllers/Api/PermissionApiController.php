<?php

namespace App\Packages\RbacManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\RbacManagement\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group RBAC Management
 */
class PermissionApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Permission::query();

        if ($request->filled('module_name')) {
            $query->where('module_name', $request->module_name);
        }

        return response()->json(
            $query->orderBy('module_name')->orderBy('name')->paginate(20)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:permissions,name',
            'description' => 'nullable|string',
            'module_name' => 'nullable|string|max:100',
        ]);

        $item = Permission::create([
            'name'        => $validated['name'],
            'guard_name'  => 'web',
            'description' => $validated['description'] ?? null,
            'module_name' => $validated['module_name'] ?? null,
        ]);

        return response()->json($item, 201);
    }

    public function show(Permission $permission): JsonResponse
    {
        return response()->json($permission->load('roles'));
    }

    public function update(Request $request, Permission $permission): JsonResponse
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

        return response()->json($permission->fresh());
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json(['message' => 'Permission deleted.']);
    }
}
