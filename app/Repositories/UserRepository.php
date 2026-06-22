<?php
namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Domain\Users\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?User { return User::find($id); }
    public function findByEmail(string $email): ?User { return User::where('email', $email)->first(); }
    public function create(array $data): User { return User::create($data); }
    public function update(User $user, array $data): User { $user->update($data); return $user->fresh(); }
    public function delete(User $user): bool { return $user->delete(); }
}
