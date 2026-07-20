<?php

namespace Tests\Unit;

use App\ViewModels\AdminDashboardViewModel;
use Tests\TestCase;

class AdminDashboardViewModelTest extends TestCase
{
    public function test_it_builds_dashboard_payload_with_core_sections(): void
    {
        $viewModel = new AdminDashboardViewModel();

        $data = $viewModel->toArray();

        $this->assertArrayHasKey('kpis', $data);
        $this->assertArrayHasKey('pipeline', $data);
        $this->assertArrayHasKey('trends', $data);
        $this->assertArrayHasKey('search', $data);
        $this->assertArrayHasKey('recent_activity', $data);
    }
}
