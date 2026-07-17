<?php

namespace App\Events;

use App\Domain\Jobs\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;

class JobCreated
{
    use Dispatchable;

    public function __construct(public Job $job)
    {
    }
}
