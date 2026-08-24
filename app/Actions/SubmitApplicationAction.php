<?php

namespace App\Actions;

use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Applications\Models\Application;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use App\Events\ApplicationSubmitted;
use Illuminate\Support\Facades\DB;

class SubmitApplicationAction
{
    /**
     * Submit a job application on behalf of a seeker.
     *
     * Wraps the creation and event dispatch in a transaction so that
     * the application record is guaranteed to exist before listeners run.
     */
    public function execute(Job $job, User $seeker, ?string $coverLetter = null, ?string $resumePath = null): Application
    {
        return DB::transaction(function () use ($job, $seeker, $coverLetter, $resumePath) {

            // Re-use an existing DRAFT application, or create a new one.
            $application = Application::updateOrCreate(
                [
                    'job_id' => $job->id,
                    'seeker_id' => $seeker->id,
                ],
                [
                    'cover_letter' => $coverLetter,
                    'resume_path' => $resumePath ?? $seeker->seekerProfile?->resume_url,
                    'status' => ApplicationStatus::SUBMITTED,
                    'applied_at' => now(),
                ],
            );

            event(new ApplicationSubmitted($application));

            return $application;
        });
    }
}
