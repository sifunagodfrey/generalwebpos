<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** License integration is deferred; signed-in user permissions still apply. */
class RequirePosLicenseForWrites
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
