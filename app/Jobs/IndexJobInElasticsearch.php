<?php

namespace App\Jobs;

use App\Domain\Jobs\Models\Job;
use App\Services\Search\ElasticsearchSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexJobInElasticsearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Job $jobRecord)
    {
    }

    public function handle(ElasticsearchSearchService $service): void
    {
        $service->indexJob($this->jobRecord);
    }
}
