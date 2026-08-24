<?php

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Services\CandidateScorer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ScoreCandidateOnApplication implements ShouldQueue
{
    public string $queue = 'default';

    public function __construct(
        private CandidateScorer $scorer,
    ) {}

    public function handle(ApplicationSubmitted $event): void
    {
        try {
            $application = $event->application->loadMissing(['job.skills', 'seekerProfile.skills']);

            $score = $this->scorer->score(
                $application->seekerProfile,
                $application->job,
            );

            $application->update(['match_score' => $score]);
        } catch (\Throwable $e) {
            Log::warning('AI candidate scoring failed', [
                'application_id' => $event->application->uuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
