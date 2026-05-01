<?php

namespace App\Packages\RbacManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\RbacManagement\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group RBAC Management
 */
class UserPermissionApiController extends Controller
{
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user'              => $user->only('id', 'name', 'email'),
            'roles'             => $user->roles,
            'direct_permissions'=> $user->getDirectPermissions(),
            'all_permissions'   => $user->getAllPermissions(),
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])->get();
        $user->syncPermissions($permissions);

        return response()->json([
            'user'              => $user->only('id', 'name', 'email'),
            'direct_permissions'=> $user->getDirectPermissions(),
        ]);
    }
}
