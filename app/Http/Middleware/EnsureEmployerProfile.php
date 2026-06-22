<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmployerProfile
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->employerProfile) {
            return redirect('/')->with('error', 'Employer profile required.');
        }

        return $next($request);
    }
}
