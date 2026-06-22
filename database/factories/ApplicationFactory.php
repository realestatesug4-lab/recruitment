<?php

namespace Database\Factories;

use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Users\Models\User;
use App\Domain\Jobs\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {

        $letters = [

            "I am a graduate from Makerere University with experience working with NGOs and private sector institutions in Kampala.",

            "I have developed strong customer service and communication skills while working in Mbarara and would be honoured to join your organisation.",

            "My experience in administrative support and ICT makes me a suitable candidate for this role."

        ];

        return [

            'status' => fake()->randomElement([
                'submitted',
                'shortlisted',
                'interview',
                'hired',
                'rejected'
            ]),



            'cover_letter' => fake()->randomElement($letters),

            'match_score' => fake()->numberBetween(40, 98),

            'applied_at' => now()
        ];
    }
}

