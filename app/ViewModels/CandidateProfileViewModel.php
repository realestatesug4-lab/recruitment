<?php
namespace App\ViewModels;

use App\Domain\Users\Models\User;

class CandidateProfileViewModel
{
    public function __construct(public User $user) {}

    public function name(): string { return $this->user->name; }
    public function skills(): array { return $this->user->seekerProfile->skills ?? []; }
}
// employer-facing candidate/seeker detail view
// can also update seeker facing page showing details
// can seeker edit their details after application filling
