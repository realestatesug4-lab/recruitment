<?php

namespace App\Listeners;

use App\Events\CompanyCreated;
use App\Jobs\IndexCompanyInElasticsearch;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexCompanyInSearch implements ShouldQueue
{
    public function handle(CompanyCreated $event): void
    {
        IndexCompanyInElasticsearch::dispatch($event->company);
    }
}
