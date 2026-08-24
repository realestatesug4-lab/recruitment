<?php

namespace App\Console\Commands;

use App\Domain\Advertising\Models\Placement;
use App\Services\ReviveAdserverService;
use Illuminate\Console\Command;

class SyncReviveZones extends Command
{
    protected $signature = 'revive:sync-zones {--force}';
    protected $description = 'Sync ad zones from Revive Adserver';

    public function handle()
    {
        if (!config('services.revive.enabled')) {
            $this->error('Revive Adserver is not enabled. Check your configuration.');
            return Command::FAILURE;
        }

        $revive = new ReviveAdserverService();

        if (!$revive->authenticate()) {
            $this->error('Failed to authenticate with Revive Adserver');
            return Command::FAILURE;
        }

        $this->info('Fetching zones from Revive Adserver...');
        $zones = $revive->getZones();

        if (empty($zones)) {
            $this->warn('No zones found in Revive Adserver');
            return Command::SUCCESS;
        }

        $this->info("Found " . count($zones) . " zones in Revive");

        foreach ($zones as $zone) {
            $this->syncZone($zone);
        }

        $this->info('Zone synchronization completed!');
        return Command::SUCCESS;
    }

    private function syncZone(array $reviveZone): void
    {
        $placement = Placement::where('revive_zone_id', $reviveZone['zoneId'])->first();

        if ($placement) {
            $placement->update([
                'name' => $reviveZone['zoneName'] ?? $placement->name,
                'status' => $reviveZone['zoneActive'] ? 'active' : 'paused',
            ]);
            $this->info("✓ Updated zone: {$reviveZone['zoneName']}");
        } else {
            $placement = Placement::create([
                'name' => $reviveZone['zoneName'] ?? 'Zone ' . $reviveZone['zoneId'],
                'slug' => \Str::slug($reviveZone['zoneName'] ?? 'zone-' . $reviveZone['zoneId']),
                'revive_zone_id' => $reviveZone['zoneId'],
                'context' => 'homepage',
                'position' => 'inline',
                'status' => $reviveZone['zoneActive'] ? 'active' : 'paused',
            ]);
            $this->info("✓ Created zone: {$reviveZone['zoneName']}");
        }
    }
}
