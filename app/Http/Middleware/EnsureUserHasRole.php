<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login'));
        }

        if (! in_array($user->role, $roles, true)) {
            // Admin can access cashier routes as well
            if (in_array('cashier', $roles, true) && $user->isAdmin()) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.'], 403);
            }

            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
