<?php

namespace App\Contracts;

use App\Domain\Jobs\Models\Job;

interface JobServiceInterface
{
    public function publish(Job $job): Job;
    public function archive(Job $job): Job;
    public function feature(Job $job): Job;
    public function expireOverdue(): int;
}
