<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Batasi rute untuk role tertentu, contoh: ->middleware('role:master')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ($roles && ! in_array($user->role, $roles, true))) {
            abort(403, 'Halaman ini tidak tersedia untuk akun Anda.');
        }

        return $next($request);
    }
}
