<?php
namespace App\Http\Controllers;

use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeekerSavedJobController extends Controller
{
    public function index(): View
    {
        $savedJobs = Auth::user()->savedJobs()
            ->with(['job.company', 'job.skills'])
            ->latest()
            ->paginate(12);

        return view('seeker.saved-jobs.index', compact('savedJobs'));
    }

    public function toggle(Job $job): RedirectResponse
    {
        $saved = Auth::user()->savedJobs()->where('job_id', $job->id)->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', 'Job removed from saved.');
        } else {
            SavedJob::create([
                'user_id' => Auth::id(),
                'job_id' => $job->id
            ]);
            return back()->with('success', 'Job saved!');
        }
    }
}
