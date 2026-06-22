<?php
namespace App\Contracts;

use App\Domain\Users\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
	public function find(int $id): ?User;
	public function findByEmail(string $email): ?User;
	public function create(array $data): User;
	public function update(User $user, array $data): User;
	public function delete(User $user): bool;
}
