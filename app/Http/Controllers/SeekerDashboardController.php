<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Domain\Applications\Models\Application;

class SeekerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $applications = Application::where('seeker_id', $user->id)->latest()->get();

        return view('seeker.dashboard.index', [
            'profile' => $user->seekerProfile,
            'applications' => $applications,
            'savedJobs' => $user->savedJobs()->with('job')->latest()->paginate(12),
            'applicationStats' => [
                'total' => $applications->count(),
                'submitted' => $applications->where('status', 'submitted')->count(),
                'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            ]
        ]);
    }
}
