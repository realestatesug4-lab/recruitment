<?php

namespace Tests\Feature;

use App\Domain\Applications\Models\Application;
use App\Domain\Companies\Models\Company;
use App\Domain\Users\Models\User;
use App\Events\ApplicationSubmitted;
use App\Events\CandidateProfileUpdated;
use App\Events\CompanyCreated;
use App\Jobs\IndexApplicationInElasticsearch;
use App\Jobs\IndexCandidateInElasticsearch;
use App\Jobs\IndexCompanyInElasticsearch;
use App\Listeners\IndexApplicationInSearch;
use App\Listeners\IndexCandidateInSearch;
use App\Listeners\IndexCompanyInSearch;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ElasticsearchIndexingEventsTest extends TestCase
{
    public function test_company_created_dispatches_company_indexing_job(): void
    {
        Queue::fake();

        $company = Company::factory()->make();
        $listener = new IndexCompanyInSearch();

        $listener->handle(new CompanyCreated($company));

        Queue::assertPushed(IndexCompanyInElasticsearch::class, function (IndexCompanyInElasticsearch $job) use ($company): bool {
            return $job->company->is($company);
        });
    }

    public function test_candidate_profile_updated_dispatches_candidate_indexing_job(): void
    {
        Queue::fake();

        $user = User::factory()->make();
        $listener = new IndexCandidateInSearch();

        $listener->handle(new CandidateProfileUpdated($user));

        Queue::assertPushed(IndexCandidateInElasticsearch::class, function (IndexCandidateInElasticsearch $job) use ($user): bool {
            return $job->user->is($user);
        });
    }

    public function test_application_submitted_dispatches_application_indexing_job(): void
    {
        Queue::fake();

        $application = Application::factory()->make();
        $listener = new IndexApplicationInSearch();

        $listener->handle(new ApplicationSubmitted($application));

        Queue::assertPushed(IndexApplicationInElasticsearch::class, function (IndexApplicationInElasticsearch $job) use ($application): bool {
            return $job->application->is($application);
        });
    }
}
