<?php

namespace App\Jobs;

use App\Services\Search\ElasticsearchSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReindexAllJobsInElasticsearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ElasticsearchSearchService $service): void
    {
        $service->reindexAllJobs();
    }
}
