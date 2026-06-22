<?php

namespace App\Services;

use App\Contracts\ApplicationRepositoryInterface;
use App\Contracts\AtsServiceInterface;
use App\Domain\Applications\Models\Application;
use App\Events\ApplicationStatusUpdated;
use App\Domain\Applications\Enums\ApplicationStatus;

class AtsService implements AtsServiceInterface
{
    private ApplicationRepositoryInterface $applications;
    private CandidateScorer $scorer;

    public function __construct(ApplicationRepositoryInterface $applications, CandidateScorer $scorer) {
        $this->applications = $applications;
        $this->scorer = $scorer;
    }

    public function scoreCandidate(Application $application): int {
        return $this->scorer->score($application->seekerProfile, $application->job);
    }

    public function moveCandidate(Application $application, ApplicationStatus $status, ?string $note = null): Application {
        $old = $application->status;

        $application = $this->applications->update($application, [
            'status'            => $status,
            'status_changed_at' => now(),
        ]);

        event(new ApplicationStatusUpdated($application, $old, $status, $note));

        return $application;
    }

    public function shortlist(Application $application): Application {
        return $this->moveCandidate($application, ApplicationStatus::SHORTLISTED);
    }

    public function scheduleInterview(Application $application, \DateTimeInterface $at): Application {
        $application->interview_at = $at;
        $application->save();
        return $this->moveCandidate($application, ApplicationStatus::INTERVIEW);
    }
}
