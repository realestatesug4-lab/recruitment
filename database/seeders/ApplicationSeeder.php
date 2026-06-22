<?php

namespace Database\Seeders;

use App\Domain\Applications\Models\Application;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        Job::all()->each(function ($job) {

            $applicants = User::inRandomOrder()
                ->take(rand(5,15))
                ->pluck('id');

            foreach ($applicants as $userId) {

                Application::factory()->create([

                    'job_id' => $job->id,

                    'seeker_id' => $userId

                ]);
            }
        });
    }
}
