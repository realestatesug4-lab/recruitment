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
        $companyId = Auth::user()->employerProfile->company_id;

        $applications = Application::whereHas('job', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })
        ->with(['job', 'seekerProfile'])
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
