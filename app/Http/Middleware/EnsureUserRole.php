<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserRole
{
    // public function handle(Request $request, Closure $next, ...$roles)
    // {
    //     dd($roles);
    //     if (!Auth::check() || Auth::user()->role !== $role) {
    //         abort(403);
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 🧩 Check if user is logged in
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();

        // 🧩 Check if user's role is in the allowed roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'Access Denied');
        }

        // ✅ Proceed if authorized
        return $next($request);
    }
}
