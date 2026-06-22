<?php
namespace App\Repositories;

use App\Contracts\ApplicationRepositoryInterface;
use App\Domain\Applications\Models\Application;
use Illuminate\Support\Collection;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function find(int $id): ?Application {
        return Application::find($id);
    }

    public function create(array $data): Application {
        return Application::create($data);
    }

    public function update(Application $application, array $data): Application {
        $application->update($data);
        return $application->fresh();
    }

    public function delete(Application $application): bool {
        return $application->delete();
    }

    public function forJob(int $jobId): Collection {
        return Application::where('job_id', $jobId)->get();
    }
}
