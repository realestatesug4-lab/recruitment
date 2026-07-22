<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SmartAdComponentTest extends TestCase
{
    public function test_it_renders_without_throwing_when_smart_ads_table_is_missing(): void
    {
        Schema::dropIfExists('smart_ads');

        $html = Blade::render('<x-smart-ad-component slug="old-mutual-unit-trust-fund" />');

        $this->assertSame('', trim($html));
    }
}
