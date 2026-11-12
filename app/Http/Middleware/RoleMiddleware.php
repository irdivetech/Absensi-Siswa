<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Strict auth check — redirect unauthenticated users to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's role name
        $userRole = trim(auth()->user()->role->nama_role);

        // Strict role check — no bypass logic, no pattern matching
        if (!in_array($userRole, $roles)) {
            // Silently redirect to the user's own dashboard (no flash message)
            return match ($userRole) {
                'admin' => redirect('/dashboard'),
                'guru'  => redirect('/guru/dashboard'),
                'siswa' => redirect('/siswa/dashboard'),
                default => abort(403, 'Unauthorized action.'),
            };
        }

        return $next($request);
    }
}
