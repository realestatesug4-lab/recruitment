<?php

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Jobs\IndexApplicationInElasticsearch;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexApplicationInSearch implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void
    {
        IndexApplicationInElasticsearch::dispatch($event->application);
    }
}
