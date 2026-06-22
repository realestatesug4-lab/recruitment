<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Jobs\Models\Job;
use App\ViewModels\JobCardViewModel;

class JobController extends Controller
{
    public function index()
    {
        $paginator = Job::published()->with('company')->paginate(15);

        return view('jobs.index', [
            'jobs' => JobCardViewModel::collection(collect($paginator->items())),
            'paginator' => $paginator,
        ]);
    }

    public function show(Job $job)
    {
        $job->load(['company', 'skills', 'categories'])->loadCount('applications');

        return view('jobs.show', [
            'job' => $job,
        ]);
    }
}
