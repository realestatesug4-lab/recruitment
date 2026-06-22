<?php

namespace Database\Seeders;

use App\Domain\Companies\Models\Company;
use App\Domain\Users\Models\EmployerProfile;
use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class EmployerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereDoesntHave('employerProfile')
            ->limit(20)
            ->get();

        Company::whereNull('owner_id')
            ->limit($users->count())
            ->get()
            ->values()
            ->each(function (Company $company, int $index) use ($users): void {
                $user = $users->get($index);

                if (!$user) {
                    return;
                }

                $user->update(['role' => 'employer']);
                $company->update(['owner_id' => $user->id]);

                EmployerProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_id' => $company->id,
                        'job_title' => 'Hiring Manager',
                        'phone' => null,
                        'bio' => 'Managing hiring activity on JobsUG.',
                    ],
                );
            });
    }
}
