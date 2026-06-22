<?php
namespace App\Contracts;

use App\Domain\Companies\Models\Company;
use Illuminate\Support\Collection;

interface CompanyRepositoryInterface
{
	public function find(int $id): ?Company;
	public function create(array $data): Company;
	public function update(Company $company, array $data): Company;
	public function delete(Company $company): bool;
	public function topRated(int $limit = 10): Collection;
}
