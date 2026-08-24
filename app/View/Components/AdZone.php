<?php

namespace App\View\Components;

use App\Domain\Advertising\Models\AdZone as AdZoneModel;
use Illuminate\View\Component;

/**
 * Reusable component for rendering ad zones
 * Usage: <x-ad-zone zone="home_top" :context="['category' => 'tech']" />
 */
class AdZone extends Component
{
    public AdZoneModel $adZone;
    public ?string $invocationCode = null;
    public string $slotClass = '';
    public array $context = [];

    public function __construct(
        public string $zone,
        ?array $context = null
    ) {
        $this->context = $context ?? [];

        // Load zone from database
        $this->adZone = AdZoneModel::whereSlug($zone)->first();

        if (!$this->adZone) {
            throw new \InvalidArgumentException("Ad zone '{$zone}' not found");
        }

        // Layout slot from zone config (top, sidebar, inline, footer)
        $this->slotClass = config("smart-ads.zones.{$zone}.slot", $this->adZone->position ?? '');

        if (!$this->adZone->isAvailable()) {
            return;
        }

        // Get invocation code from ad server
        // This would be retrieved from config or service
        $this->loadInvocationCode();
    }

    private function loadInvocationCode(): void
    {
        // Explicit invocation code from config takes precedence
        $this->invocationCode = config("smart-ads.zones.{$this->zone}.invocation_code");

        if ($this->invocationCode) {
            return;
        }

        // Fall back to the standard Revive async invocation tag built from
        // the zone's mapped Revive zone ID
        $this->invocationCode = $this->buildReviveInvocationCode();
    }

    /**
     * Build the standard Revive Adserver asyncjs invocation tag for the zone.
     */
    private function buildReviveInvocationCode(): ?string
    {
        $reviveZoneId = config("smart-ads.zones.{$this->zone}.revive_zone_id")
            ?? $this->adZone->revive_zone_id;

        if (!$reviveZoneId) {
            return null;
        }

        $reviveUrl = rtrim(config('services.revive.url'), '/');

        return '<ins data-revive-zoneid="' . e($reviveZoneId) . '">'
            . '<script async src="' . e($reviveUrl) . '/www/delivery/asyncjs.php"></script>'
            . '</ins>';
    }

    public function render()
    {
        return view('components.ad-zone', [
            'zone' => $this->adZone,
            'invocationCode' => $this->invocationCode,
            'slotClass' => $this->slotClass,
            'context' => $this->context,
        ]);
    }
}
