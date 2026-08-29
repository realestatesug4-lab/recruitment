<?php
namespace App\Http\Controllers;

use App\Domain\Applications\Models\Application;
use App\Domain\Jobs\Models\Job;
use App\ViewModels\ApplicationCardViewModel;
use Illuminate\Support\Facades\Auth;

class EmployerApplicationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $companyId = $user->employerProfile?->company_id;

        if (! $companyId) {
            return redirect()->route('employer.onboarding.create')->with('error', 'Set up your company profile first.');
        }

        $applications = Application::whereHas('job', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })
        ->with(['job.company', 'seekerProfile'])
        ->paginate(20);

        return view('employer.applications.index', [
            'applications' => $applications->map(fn($app) => new ApplicationCardViewModel($app)),
            'paginator' => $applications,
        ]);
    }

    public function show(Application $application)
    {
        return view('employer.applications.show', [
            'application' => new ApplicationCardViewModel($application),
        ]);
    }
}
