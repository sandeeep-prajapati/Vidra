<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (! $request->user()) {
            return $this->deny($request, 401);
        }

        foreach ($permissions as $permission) {
            if ($request->user()->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        return $this->deny($request, 403);
    }

    private function deny(Request $request, int $status): Response
    {
        if ($request->expectsJson()) {
            $message = $status === 401 ? 'Unauthenticated.' : 'Forbidden.';
            return response()->json(['message' => $message], $status);
        }

        if ($status === 401) {
            return redirect()->route('login');
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
