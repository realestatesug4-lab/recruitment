<?php

namespace App\Jobs;

use App\Domain\Applications\Models\Application;
use App\Services\Search\ElasticsearchIndexService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexApplicationInElasticsearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Application $application)
    {
    }

    public function handle(ElasticsearchIndexService $service): void
    {
        $service->indexApplication($this->application);
    }
}
