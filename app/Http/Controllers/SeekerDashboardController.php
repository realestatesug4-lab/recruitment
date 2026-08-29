<?php
namespace App\Http\Controllers;

use App\Domain\Applications\Models\Application;
use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Enums\JobStatus;
use App\ViewModels\SeekerDashboardViewModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SeekerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        $applications = Application::with(['job.company', 'statusHistory'])
            ->where('seeker_id', $user->id)
            ->latest()
            ->get();

        // Recommended jobs based on profile skills
        $recommendedJobs = collect();
        $profile = $user->seekerProfile?->load('skills');

        if ($profile && $profile->skills->isNotEmpty()) {
            $skillIds = $profile->skills->pluck('id')->toArray();

            $recommendedJobs = Job::published()
                ->with(['company', 'skills'])
                ->whereHas('skills', fn ($q) => $q->whereIn('skills.id', $skillIds))
                ->whereDoesntHave('applications', fn ($q) => $q->where('seeker_id', $user->id))
                ->latest('published_at')
                ->take(6)
                ->get();
        }

        $viewModel = new SeekerDashboardViewModel(
            user: $user,
            profile: $profile,
            applications: $applications,
            savedJobs: $user->savedJobs()->with('job.company')->latest()->paginate(6),
            recommendedJobs: $recommendedJobs,
        );

        return view('seeker.dashboard.index', [
            'viewModel' => $viewModel,
        ]);
    }
}
