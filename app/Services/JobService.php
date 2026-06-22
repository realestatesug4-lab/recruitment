<?php

namespace App\Services;

use App\Contracts\{JobServiceInterface, JobRepositoryInterface};
use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Enums\JobStatus;
use App\Events\JobPublished;

class JobService implements JobServiceInterface
{
    public function __construct(
        private JobRepositoryInterface $jobs
    ) {}

    public function publish(Job $job): Job {
        $job = $this->jobs->update($job, [
            'status'       => JobStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        event(new JobPublished($job));

        return $job;
    }

    public function archive(Job $job): Job {
        return $this->jobs->update($job, ['status' => JobStatus::ARCHIVED]);
    }

    public function feature(Job $job): Job {
        return $this->jobs->update($job, ['is_featured' => true]);
    }

    public function expireOverdue(): int {
        return Job::where('status', JobStatus::PUBLISHED)
            ->where('deadline', '<', now())
            ->update(['status' => JobStatus::CLOSED]);
    }
}
