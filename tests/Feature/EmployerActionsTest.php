<?php

namespace Tests\Feature;

use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Models\JobCategory;
use App\Domain\Jobs\Models\Skill;
use App\Domain\Users\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployerActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_update_their_company_profile_and_upload_a_logo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['owner_id' => $user->id]);
        EmployerProfile::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'job_title' => 'Hiring Manager',
        ]);

        $response = $this->actingAs($user)->put('/employer/company', [
            'name' => 'Acme Labs',
            'description' => 'Building the future of work.',
            'industry' => 'Technology',
            'location' => 'Kampala',
            'size' => '120',
            'website' => 'https://acme.example',
            'logo' => UploadedFile::fake()->image('logo.png'),
        ]);

        $response->assertRedirect(route('employer.dashboard'));

        $company->refresh();
        $this->assertSame('Acme Labs', $company->name);
        $this->assertSame('Technology', $company->industry);
        $this->assertSame('Kampala', $company->location);
        $this->assertNotNull($company->logo_url);
        Storage::disk('public')->assertExists($company->logo_url);
    }

    public function test_employer_can_create_a_job_draft(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        $company = Company::factory()->create(['owner_id' => $user->id]);
        EmployerProfile::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'job_title' => 'Hiring Manager',
        ]);

        $category = JobCategory::create(['name' => 'Engineering', 'slug' => 'engineering']);
        $skill = Skill::create(['name' => 'Laravel', 'slug' => 'laravel']);

        $response = $this->actingAs($user)->post('/employer/jobs', [
            'title' => 'Senior Laravel Engineer',
            'description' => 'Build products for the next generation of recruiting tools.',
            'type' => 'full-time',
            'experience_level' => 'senior',
            'location' => 'Kampala',
            'salary_min' => 5000000,
            'salary_max' => 9000000,
            'deadline' => now()->addMonth()->format('Y-m-d'),
            'categories' => [$category->id],
            'skills' => [$skill->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_posts', [
            'company_id' => $company->id,
            'title' => 'Senior Laravel Engineer',
        ]);
    }
}
