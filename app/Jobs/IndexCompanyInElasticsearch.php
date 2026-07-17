<?php

namespace App\Jobs;

use App\Domain\Companies\Models\Company;
use App\Services\Search\ElasticsearchIndexService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexCompanyInElasticsearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Company $company)
    {
    }

    public function handle(ElasticsearchIndexService $service): void
    {
        $service->indexCompany($this->company);
    }
}
