<?php

namespace App\Listeners;

use App\Events\CandidateProfileUpdated;
use App\Jobs\IndexCandidateInElasticsearch;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexCandidateInSearch implements ShouldQueue
{
    public function handle(CandidateProfileUpdated $event): void
    {
        IndexCandidateInElasticsearch::dispatch($event->user);
    }
}
