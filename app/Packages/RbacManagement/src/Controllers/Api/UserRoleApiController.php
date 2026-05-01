<?php

namespace App\Packages\RbacManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\RbacManagement\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group RBAC Management
 */
class UserRoleApiController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);

        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user->load('roles', 'permissions'));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'roles'   => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['roles'] ?? [])->get();
        $user->syncRoles($roles);

        return response()->json($user->load('roles'));
    }
}
