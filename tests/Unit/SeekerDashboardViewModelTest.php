<?php

namespace Tests\Unit;

use App\Domain\Users\Models\User;
use App\ViewModels\SeekerDashboardViewModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SeekerDashboardViewModelTest extends TestCase
{
    public function test_profile_stat_value_is_numeric(): void
    {
        $viewModel = new SeekerDashboardViewModel(
            new User(),
            null,
            new Collection(),
            new LengthAwarePaginator([], 0, 15),
        );

        $stats = $viewModel->stats();

        $this->assertSame('Profile', $stats[2]['label']);
        $this->assertIsInt($stats[2]['value']);
        $this->assertSame(24, $stats[2]['value']);
    }
}
