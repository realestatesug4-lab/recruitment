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
        $profile = Auth::user()->employerProfile;

        if (!$profile) {
            return redirect()->route('employer.onboarding.create')->with('error', 'Set up your company profile first.');
        }

        $company = $profile->company;
        $companyId = $company->id;

        $viewModel = new EmployerDashboardViewModel(
            company: $company,
            openJobs: Job::where('company_id', $companyId)->published()->count(),
            draftJobs: Job::where('company_id', $companyId)->where('status', 'draft')->count(),
            totalApplications: Application::whereHas('job', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->count(),
            recentApplications: Application::whereHas('job', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->with('seekerProfile')
            ->latest()
            ->limit(5)
            ->get(),
        );

        return view('employer.dashboard', ['viewModel' => $viewModel]);
    }
}
