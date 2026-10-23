<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissions)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Super Administrators bypass all permission checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Split permissions by pipe (OR condition)
        $perms = explode('|', $permissions);

        foreach ($perms as $perm) {
            if ($user->hasPermission(trim($perm))) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this area.');
    }
}