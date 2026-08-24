<?php

namespace App\Console\Commands;

use App\Domain\Advertising\Models\Placement;
use App\Domain\Advertising\Models\AdStat;
use App\Services\ReviveAdserverService;
use Illuminate\Console\Command;

class SyncReviveStats extends Command
{
    protected $signature = 'revive:sync-stats {--days=30}';
    protected $description = 'Sync ad statistics from Revive Adserver';

    public function handle()
    {
        if (!config('services.revive.enabled')) {
            $this->error('Revive Adserver is not enabled. Check your configuration.');
            return Command::FAILURE;
        }

        $days = (int)$this->option('days');
        $revive = new ReviveAdserverService();

        if (!$revive->authenticate()) {
            $this->error('Failed to authenticate with Revive Adserver');
            return Command::FAILURE;
        }

        $placements = Placement::whereNotNull('revive_zone_id')->get();

        if ($placements->isEmpty()) {
            $this->warn('No placements linked to Revive zones');
            return Command::SUCCESS;
        }

        $this->info("Syncing stats for " . count($placements) . " placements (last {$days} days)...");

        foreach ($placements as $placement) {
            $this->syncPlacementStats($placement, $revive, $days);
        }

        $this->info('Statistics synchronization completed!');
        return Command::SUCCESS;
    }

    private function syncPlacementStats(Placement $placement, ReviveAdserverService $revive, int $days): void
    {
        try {
            $stats = $revive->getZoneStats(
                $placement->revive_zone_id,
                now()->subDays($days)->toDateString(),
                now()->toDateString()
            );

            if (empty($stats)) {
                $this->warn("  ✗ No stats found for {$placement->name}");
                return;
            }

            $count = 0;
            foreach ($stats as $stat) {
                if (is_array($stat)) {
                    $date = $stat['date'] ?? now();

                    AdStat::updateOrCreate(
                        [
                            'placement_id' => $placement->id,
                            'date' => $date,
                        ],
                        [
                            'revive_zone_id' => $placement->revive_zone_id,
                            'impressions' => $stat['impressions'] ?? 0,
                            'clicks' => $stat['clicks'] ?? 0,
                            'revenue' => $stat['revenue'] ?? 0,
                            'ctr' => $this->calculateCTR($stat['clicks'] ?? 0, $stat['impressions'] ?? 0),
                            'ecpm' => $this->calculateECPM($stat['revenue'] ?? 0, $stat['impressions'] ?? 0),
                        ]
                    );
                    $count++;
                }
            }

            $this->info("  ✓ Updated {$count} stat records for {$placement->name}");
        } catch (\Exception $e) {
            $this->error("  ✗ Failed to sync stats for {$placement->name}: " . $e->getMessage());
        }
    }

    private function calculateCTR($clicks, $impressions): float
    {
        if ($impressions <= 0) {
            return 0;
        }
        return ($clicks / $impressions) * 100;
    }

    private function calculateECPM($revenue, $impressions): float
    {
        if ($impressions <= 0) {
            return 0;
        }
        return ($revenue / ($impressions / 1000));
    }
}
