<?php
namespace App\Repositories;

use App\Contracts\CompanyRepositoryInterface;
use App\Domain\Companies\Models\Company;
use Illuminate\Support\Collection;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function find(int $id): ?Company { return Company::find($id); }
    public function create(array $data): Company { return Company::create($data); }
    public function update(Company $company, array $data): Company { $company->update($data); return $company->fresh(); }
    public function delete(Company $company): bool { return $company->delete(); }
    public function topRated(int $limit = 10): Collection { return Company::take($limit)->get(); }
}
