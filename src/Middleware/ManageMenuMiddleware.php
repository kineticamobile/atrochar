<?php

namespace Kineticamobile\Atrochar\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ManageMenuMiddleware
{
    public function handle($request, Closure $next)
    {
        if ( ! $request->user()->canManageMenus()) {
            abort(403);
        }

        return $next($request);
    }
}
