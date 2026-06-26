<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->role === 'employer' || $user->employerProfile) {
            return redirect()->route('employer.dashboard');
        }

        if ($user->role === 'admin' || $user->hasRole('admin')) {
            return redirect('/admin');
        }

        return redirect()->route('seeker.dashboard');
    }
}
