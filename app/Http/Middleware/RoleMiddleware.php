<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Verificar que el usuario tenga el rol requerido
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'No tiene permiso para acceder a esta seccion.');
        }

        if (!$request->user()->active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Su cuenta ha sido desactivada.',
            ]);
        }

        return $next($request);
    }
}
