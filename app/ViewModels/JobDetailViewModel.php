<?php
namespace App\ViewModels;

use App\Domain\Jobs\Models\Job;

class JobDetailViewModel
{
    public function __construct(public Job $job) {}

    public function title(): string { return $this->job->title; }
    public function companyName(): string { return $this->job->company->name; }
    public function descriptionHtml(): string { return $this->job->description; }
}
// job detail page - formats JSON-LD schema too
