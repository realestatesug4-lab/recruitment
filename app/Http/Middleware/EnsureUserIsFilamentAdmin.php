<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnsureUserIsFilamentAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        $isAdmin = false;

        if (method_exists($user, 'hasRole')) {
            $isAdmin = $user->hasRole('admin');
        }

        if (! $isAdmin && isset($user->role) && $user->role === 'admin') {
            $isAdmin = true;
        }

        if (! $isAdmin) {
            abort(403, 'You do not have access to the admin panel.');
        }

        return $next($request);
    }
}
