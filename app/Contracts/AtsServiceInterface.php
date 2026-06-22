<?php

namespace App\Contracts;

use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Enums\ApplicationStatus;

interface AtsServiceInterface
{
    public function scoreCandidate(Application $application): int;
    public function moveCandidate(Application $application, ApplicationStatus $status, ?string $note = null): Application;
    public function shortlist(Application $application): Application;
    public function scheduleInterview(Application $application, \DateTimeInterface $at): Application;
}
