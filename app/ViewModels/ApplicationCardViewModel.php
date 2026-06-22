<?php
namespace App\ViewModels;

use App\Domain\Applications\Models\Application;

class ApplicationCardViewModel
{
    public function __construct(public Application $application) {}

    public function candidateName(): string { return $this->application->seekerProfile->name ?? 'Candidate'; }
    public function appliedAgo(): string { return $this->application->created_at?->diffForHumans() ?? 'Just now'; }
}
// ATS Kanboard board cards in Filament
// also create stepped application process blade template with relevant animation using gsap
