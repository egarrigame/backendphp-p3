<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) { // check login
            return redirect('/login');
        }

        if (Auth::user()->rol !== $role) { // check de rol
            abort(403, 'Acceso Denegado. Tu rol de ' . Auth::user()->rol . ' no tiene permisos para esta sección.');
        }
        return $next($request);
    }
}
