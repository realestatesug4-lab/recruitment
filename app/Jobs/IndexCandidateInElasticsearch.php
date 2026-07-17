<?php

namespace App\Jobs;

use App\Domain\Users\Models\User;
use App\Services\Search\ElasticsearchIndexService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexCandidateInElasticsearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function handle(ElasticsearchIndexService $service): void
    {
        $service->indexCandidate($this->user);
    }
}
