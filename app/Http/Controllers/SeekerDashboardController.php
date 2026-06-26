<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Domain\Applications\Models\Application;
use App\ViewModels\SeekerDashboardViewModel;

class SeekerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $applications = Application::where('seeker_id', $user->id)->latest()->get();

        $viewModel = new SeekerDashboardViewModel(
            user: $user,
            profile: $user->seekerProfile,
            applications: $applications,
            savedJobs: $user->savedJobs()->with('job')->latest()->paginate(12),
        );

        return view('seeker.dashboard.index', [
            'viewModel' => $viewModel,
        ]);
    }
}
