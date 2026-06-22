<?php
namespace App\Http\Controllers;

use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class SeekerSavedJobController extends Controller
{
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
