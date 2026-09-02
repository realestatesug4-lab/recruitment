<?php

namespace Tests\Feature\Advertising;

use App\Contracts\Advertising\AdServerInterface;
use App\Domain\Users\Models\SeekerProfile;
use App\Models\User;
use App\Services\ReviveAdserverService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReviveServiceBindingTest extends TestCase
{
    public function test_revive_service_implements_the_adserver_contract(): void
    {
        $this->assertInstanceOf(AdServerInterface::class, new ReviveAdserverService());
    }

    public function test_revive_service_is_bound_in_the_container(): void
    {
        $service = $this->app->make(AdServerInterface::class);

        $this->assertInstanceOf(ReviveAdserverService::class, $service);
    }

    public function test_revive_service_supports_legacy_zone_stats_alias(): void
    {
        $this->assertTrue(method_exists(ReviveAdserverService::class, 'getZoneStats'));
    }

    public function test_seeker_profile_exposes_user_relationship(): void
    {
        $this->assertTrue(method_exists(SeekerProfile::class, 'user'));
    }

    public function test_user_password_cast_hashes_once_when_created_from_plain_text(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin-user-' . uniqid() . '@example.com',
            'role' => 'admin',
            'password' => 'PlainPassword123!',
        ]);

        $this->assertTrue(Hash::check('PlainPassword123!', $user->password));
        $this->assertNotSame(Hash::make('PlainPassword123!'), $user->password);
    }
}
