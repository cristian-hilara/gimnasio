<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('redirect');
        }

        $allowedRoles = explode('|', $role);

        if (!in_array(Auth::user()->rol, $allowedRoles)) {
            abort(403, 'User does not have the right roles.');
        }

        return $next($request);
    }
}
