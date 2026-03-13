<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirigir según el rol del usuario
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }

                // Para docentes y otros usuarios, redirigir a la biblioteca
                return redirect()->route('biblioteca.index');
            }
        }

        return $next($request);
    }
}
