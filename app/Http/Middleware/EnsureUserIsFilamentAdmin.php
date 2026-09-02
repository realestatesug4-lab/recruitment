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
            return redirect()->guest(route('admin.login'));
        }

        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : false;

        if (! $isAdmin) {
            abort(403, 'You do not have access to the admin panel.');
        }

        return $next($request);
    }
}
