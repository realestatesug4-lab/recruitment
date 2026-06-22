<?php

namespace App\Actions;

use App\Contracts\JobRepositoryInterface;
use App\DTOs\JobData;
use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Enums\JobStatus;
use App\Events\JobCreated;

class PostJobAction
{
    public function __construct(
        private JobRepositoryInterface $jobs
    ) {}

    public function execute(JobData $data, int $companyId): Job {
        $job = $this->jobs->create([
            'company_id'       => $companyId,
            'title'            => $data->title,
            'slug'             => str($data->title)->slug() . '-' . uniqid(),
            'description'      => $data->description,
            'job_type'         => $data->type,
            'experience_level' => $data->experienceLevel,
            'location'         => $data->location,
            'salary_min'       => $data->salaryRange?->min,
            'salary_max'       => $data->salaryRange?->max,
            'deadline'         => $data->deadline,
            'status'           => JobStatus::DRAFT,
        ]);

        $job->categories()->sync($data->categoryIds);
        $job->skills()->sync($data->skills);

        event(new JobCreated($job));

        return $job;
    }
}
