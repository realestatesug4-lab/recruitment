<?php
namespace App\Listeners;

use App\Events\JobPublished;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCompanyFollowers implements ShouldQueue
{
    public function handle(JobPublished $event): void {
        // placeholder: notify followers about new job
    }
}
