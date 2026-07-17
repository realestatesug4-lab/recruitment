<?php

namespace App\Events;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class CandidateProfileUpdated
{
    use Dispatchable;

    public function __construct(public User $user)
    {
    }
}
