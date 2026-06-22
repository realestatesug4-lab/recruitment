<?php

namespace Database\Factories;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        $firstNames = [
            'Ivan','Sarah','Grace','Brian',
            'Doreen','Paul','David','Faith',
            'Patricia','Aisha','Joel','Brenda',
            'Ronald','Sharon','Moses','Esther'
        ];

        $lastNames = [
            'Tumusiime','Nakato','Kato',
            'Achieng','Okello','Byaruhanga',
            'Mugisha','Twinomugisha',
            'Asiimwe','Atwine','Mwebesa',
            'Turyahikayo','Nansubuga'
        ];

        $firstName = fake()->randomElement($firstNames);
        $lastName = fake()->randomElement($lastNames);

        return [
            'name' => "$firstName $lastName",

            'email' => fake()->unique()->userName()
                .'@gmail.com',

            'email_verified_at' => now(),

            'password' => static::$password ??=
                Hash::make('password'),

            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
