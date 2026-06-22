<?php

namespace Database\Seeders;

use App\Domain\Jobs\Models\JobCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'description' => 'Full-stack and frontend web development roles'],
            ['name' => 'Backend Development', 'description' => 'Server-side and API development positions'],
            ['name' => 'Mobile Development', 'description' => 'iOS, Android, and cross-platform mobile apps'],
            ['name' => 'DevOps & Infrastructure', 'description' => 'Cloud, infrastructure, and deployment roles'],
            ['name' => 'Data Science', 'description' => 'Data analysis, machine learning, and analytics'],
            ['name' => 'UI/UX Design', 'description' => 'User interface and user experience design'],
            ['name' => 'Project Management', 'description' => 'Project coordination and management roles'],
            ['name' => 'Digital Marketing', 'description' => 'SEO, content, and digital marketing positions'],
            ['name' => 'Business Analysis', 'description' => 'Requirements gathering and process analysis'],
            ['name' => 'Quality Assurance', 'description' => 'Software testing and quality assurance roles'],
        ];

        foreach ($categories as $category) {
            JobCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category
            );
        }
    }
}
