<?php

namespace Database\Seeders;

use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 200 Ugandan users
        User::factory()
            ->count(200)
            ->create();

        // Demo job seeker
        User::create([
            'name' => 'Ivan Tumusiime',
            'email' => 'ivan.tumusiime@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        // Demo recruiter
        User::create([
            'name' => 'Sarah Nakato',
            'email' => 'sarah.nakato@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        // Admin account
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@jobsug.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now()
        ]);
    }
}
