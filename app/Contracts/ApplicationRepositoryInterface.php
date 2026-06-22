<?php
namespace App\Contracts;

use App\Domain\Applications\Models\Application;
use Illuminate\Support\Collection;

interface ApplicationRepositoryInterface
{
	public function find(int $id): ?Application;
	public function create(array $data): Application;
	public function update(Application $application, array $data): Application;
	public function delete(Application $application): bool;
	public function forJob(int $jobId): Collection;
}
