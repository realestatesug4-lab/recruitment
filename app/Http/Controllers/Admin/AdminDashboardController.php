<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AdminDashboardController extends Controller
{
    /**
     * Redirect to admin panel
     */
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        if (! $user || ! method_exists($user, 'isAdmin') || ! $user->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return redirect()->route('filament.admin.pages.dashboard');
    }
}
