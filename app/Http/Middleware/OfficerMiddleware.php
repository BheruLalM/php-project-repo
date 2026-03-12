<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OfficerMiddleware
{
    /**
     * Allow access to both 'admin' and 'officer' roles.
     * Redirects unauthenticated users to login.
     * Aborts with 403 for any other role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! in_array(Auth::user()->role, ['admin', 'officer'])) {
            abort(403, 'Access Denied.');
        }

        return $next($request);
    }
}
