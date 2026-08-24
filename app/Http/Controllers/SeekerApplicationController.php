<?php
namespace App\Http\Controllers;

use App\Actions\SubmitApplicationAction;
use App\Domain\Jobs\Models\Job;
use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Enums\ApplicationStatus;
use App\Http\Requests\StoreApplicationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeekerApplicationController extends Controller
{
    public function create(Job $job): View
    {
        $profile = Auth::user()->seekerProfile?->load('skills');

        return view('seeker.applications.create', [
            'job' => $job,
            'profile' => $profile,
            'completion' => $this->profileCompletion($profile),
            'missingItems' => $this->missingProfileItems($profile),
            'existingApplication' => Application::where('job_id', $job->id)
                ->where('seeker_id', Auth::id())
                ->first(),
        ]);
    }

    public function store(Job $job, StoreApplicationRequest $request, SubmitApplicationAction $action): RedirectResponse
    {
        $existingApplication = Application::where('job_id', $job->id)
            ->where('seeker_id', Auth::id())
            ->first();

        if ($existingApplication && $existingApplication->status !== ApplicationStatus::DRAFT) {
            return redirect()
                ->route('seeker.applications.progress')
                ->with('success', 'You have already applied for this job.');
        }

        $resumePath = $request->file('resume')?->store('resumes', 'public');

        $action->execute(
            job: $job,
            seeker: Auth::user(),
            coverLetter: $request->input('cover_letter'),
            resumePath: $resumePath,
        );

        return redirect()
            ->route('seeker.applications.thankyou', $job->slug)
            ->with('success', 'Your application has been submitted.');
    }

    public function thankyou(Job $job): View
    {
        return view('seeker.applications.thankyou', [
            'job' => $job,
        ]);
    }

    public function show(Application $application): View
    {
        abort_if($application->seeker_id !== Auth::id(), 403);

        $application->load(['job.company', 'job.skills', 'statusHistory.changedBy']);

        return view('seeker.applications.show', [
            'application' => $application,
        ]);
    }

    public function progress(): View
    {
        return view('seeker.applications.progress', [
            'applications' => Application::with(['job.company'])
                ->where('seeker_id', Auth::id())
                ->latest()
                ->get(),
        ]);
    }

    private function profileCompletion($profile): int
    {
        if (!$profile) {
            return 0;
        }

        $checks = [
            filled($profile->headline),
            filled($profile->bio),
            filled($profile->location),
            filled($profile->experience_level),
            filled($profile->resume_url),
            $profile->skills->isNotEmpty(),
        ];

        return (int) round((collect($checks)->filter()->count() / count($checks)) * 100);
    }

    private function missingProfileItems($profile): array
    {
        if (!$profile) {
            return ['Profile summary', 'Location', 'Experience level', 'Skills', 'CV'];
        }

        return collect([
            'Profile headline' => blank($profile->headline),
            'Profile summary' => blank($profile->bio),
            'Location' => blank($profile->location),
            'Experience level' => blank($profile->experience_level),
            'Skills' => $profile->skills->isEmpty(),
            'CV' => blank($profile->resume_url),
        ])->filter()->keys()->values()->all();
    }
}
