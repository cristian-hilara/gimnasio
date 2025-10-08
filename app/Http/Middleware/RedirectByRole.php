<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $rol = Auth::user()->rol;

            return match ($rol) {
                'ADMINISTRADOR', 'RECEPCIONISTA' => redirect()->route('panel.recepcionista'),
                'CLIENTE' => redirect()->route('panel.cliente'),
                default => abort(403),
            };
        }

        return $next($request);
    }
}
