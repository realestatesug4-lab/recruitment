<?php
namespace App\Events;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class UserRegistered
{
    use Dispatchable;

    public function __construct(public User $user) {}
}
// triggered when new seeker or employer accounted created
