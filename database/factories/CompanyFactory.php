<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Domain\Companies\Models\Company;

class CompanyFactory extends Factory
{
    protected $model = Company::class;
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,

            'slug' => Str::slug('MTN Uganda'),

            'description' => fake()->paragraph(),

            'industry' => fake()->randomElement([
                'Technology',
                'Healthcare',
                'Finance',
                'Education',
                'Telecommunications'
            ]),

            'website' => fake()->url(),

            'verification_status' => 'verified'
        ];
    }
}
