<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Penggunaan di route:
     *   ->middleware('role:owner')
     *   ->middleware('role:owner,admin')    ← multiple roles (OR)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.', 'code' => 401], 401);
            }

            return redirect()->route('login');
        }

        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        $allowedRoles = array_map(fn(string $r) => UserRole::from($r), $roles);

        if (! in_array($user->role, $allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.', 'code' => 403], 403);
            }

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
