<?php
namespace App\Events;

use App\Domain\Jobs\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;

class JobPublished
{
    use Dispatchable;

    public function __construct(public Job $job) {}
}
// triggered when a job moves to the published status
