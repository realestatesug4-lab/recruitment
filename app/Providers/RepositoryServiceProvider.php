<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\{
    JobRepositoryInterface,
    UserRepositoryInterface,
    CompanyRepositoryInterface,
    ApplicationRepositoryInterface,
    SearchServiceInterface,
};
use App\Repositories\{
    JobRepository, UserRepository, CompanyRepository, ApplicationRepository
};
use App\Services\Search\ElasticsearchSearchService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);

        // Swappable search backend — change ONE line to migrate engines
        $this->app->bind(SearchServiceInterface::class, ElasticsearchSearchService::class);
    }
}

//App\Providers\RepositoryServiceProvider::class,
