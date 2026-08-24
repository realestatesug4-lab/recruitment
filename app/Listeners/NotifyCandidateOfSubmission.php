<?php

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Notifications\ApplicationConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyCandidateOfSubmission implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void
    {
        try {
            $application = $event->application->loadMissing(['job.company', 'seekerProfile']);

            // The seeker IS the user — notify them via their User model
            $seeker = $application->seekerProfile;

            if ($seeker) {
                $seeker->notify(new ApplicationConfirmationNotification($application));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to notify candidate of submission', [
                'application_id' => $event->application->uuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
