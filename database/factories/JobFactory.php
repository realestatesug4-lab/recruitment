<?php

namespace Database\Factories;
use App\Domain\Companies\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Domain\Jobs\Models\Job;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $title = fake()->randomElement([
            'Software Engineer',
            'Backend Developer',
            'Frontend Developer',
            'DevOps Engineer',
            'Data Analyst',
            'Product Manager',
            'UI/UX Designer',
            'Cybersecurity Specialist',
            'QA Engineer',
            'Mobile Developer'
        ]);

        return [
            'company_id' => Company::factory(),

            'title' => $title,

            'slug' => Str::slug(
                $title . '-' . fake()->unique()->numberBetween(1000, 9999)
            ),

            'description' => fake()->paragraphs(5, true),

            'job_type' => fake()->randomElement([
                'full-time',
                'contract',
                'remote'
            ]),

            'experience_level' => fake()->randomElement([
                'junior',
                'mid',
                'senior'
            ]),

            'salary_min' => fake()->numberBetween(500000, 3000000),

            'salary_max' => fake()->numberBetween(3500000, 12000000),

            'location' => fake()->randomElement([
                'Kampala',
                'Entebbe',
                'Mbarara',
                'Gulu',
                'Jinja',
                'Remote'
            ]),

            'status' => 'published',

            'published_at' => now(),

            'deadline' => now()->addDays(
                fake()->numberBetween(14, 90)
            ),
        ];
    }
}
