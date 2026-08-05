<?php

namespace Tests\Feature;

use App\Domain\Companies\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCompanyResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_company_admin_index(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_super_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/companies');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_company_admin_index(): void
    {
        $user = User::factory()->create([
            'role' => 'employer',
        ]);

        $response = $this->actingAs($user)->get('/admin/companies');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_company_create_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_super_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/companies/create');

        $response->assertStatus(200);
        $response->assertSeeText('Verification Status');
    }

    public function test_admin_can_access_company_edit_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_super_admin' => true,
        ]);

        $company = Company::factory()->create([
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get("/admin/companies/{$company->id}/edit");

        $response->assertStatus(200);
        $response->assertSeeText('Verification Status');
    }
}
