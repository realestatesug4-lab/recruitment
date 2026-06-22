<?php

namespace App\Events;

use App\Events\ApplicationSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunAiScoring implements ShouldQueue
{
    public function __construct() {}

    public function handle(ApplicationSubmitted $event): void {
        $scorer = app(\App\Services\CandidateScorer::class);

        $score = $scorer->score(
            $event->application->seekerProfile,
            $event->application->job
        );

        $event->application->update(['match_score' => $score]);
    }
}
