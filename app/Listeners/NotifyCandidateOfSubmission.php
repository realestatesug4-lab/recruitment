<?php
namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCandidateOfSubmission implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void {
        // placeholder: notify candidate (implement Notification later)
        $candidate = $event->application->seekerProfile;
        if ($candidate && method_exists($candidate, 'notify')) {
            $candidate->notify(new \App\Events\NewApplicationReceived($event->application));
        }
    }
}
