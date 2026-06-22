<?php

namespace Database\Seeders;

use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function ($company) {
            Job::factory()
                    ->count(rand(5, 20))
                    ->create([
                        'company_id' => $company->id
                    ]);
            });
    }
}
