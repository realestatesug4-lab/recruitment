<?php

namespace Database\Seeders;

use App\Domain\Jobs\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'React', 'Vue.js',
            'Python', 'Django', 'FastAPI', 'Node.js', 'Express.js',
            'SQL', 'MySQL', 'PostgreSQL', 'MongoDB', 'Firebase',
            'HTML', 'CSS', 'Tailwind CSS', 'Bootstrap',
            'Git', 'Docker', 'Kubernetes', 'AWS', 'Google Cloud', 'Azure',
            'REST API', 'GraphQL', 'Microservices', 'CI/CD',
            'Project Management', 'Agile', 'Scrum', 'Kanban',
            'SEO', 'Content Writing', 'Copywriting', 'Social Media Marketing',
            'Email Marketing', 'Analytics', 'Data Analysis', 'Excel',
            'UI Design', 'UX Design', 'Figma', 'Adobe XD', 'Sketch',
            'Communication', 'Leadership', 'Problem Solving', 'Teamwork'
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['slug' => Str::slug($skill)],
                ['name' => $skill]
            );
        }
    }
}
