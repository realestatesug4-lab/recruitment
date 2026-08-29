<?php
namespace App\Http\Controllers;

use App\Domain\Jobs\Models\Job;
use App\Domain\Applications\Models\Application;
use App\ViewModels\EmployerDashboardViewModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployerDashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $profile = $user->employerProfile()->with('company')->first();

        if (! $profile) {
            return redirect()->route('employer.onboarding.create')->with('error', 'Set up your company profile first.');
        }

        $company = $profile->company;
        $companyId = $company->id;

        $recentApplications = Application::query()
            ->whereHas('job', fn ($q) => $q->where('company_id', $companyId))
            ->with(['seekerProfile', 'job'])
            ->latest()
            ->limit(5)
            ->get();

        $viewModel = new EmployerDashboardViewModel(
            company: $company,
            openJobs: Job::query()->where('company_id', $companyId)->published()->count(),
            draftJobs: Job::query()->where('company_id', $companyId)->where('status', 'draft')->count(),
            totalApplications: Application::query()->whereHas('job', fn ($q) => $q->where('company_id', $companyId))->count(),
            recentApplications: $recentApplications,
        );

        return view('employer.dashboard', ['viewModel' => $viewModel]);
    }
}
