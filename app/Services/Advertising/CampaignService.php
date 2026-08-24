<?php

namespace App\Services\Advertising;

use App\Contracts\Advertising\AdServerInterface;
use App\Domain\Advertising\Models\AdCampaign;
use App\Domain\Advertising\Models\AdPlacement;
use App\Domain\Advertising\Models\AdOrder;
use App\Domain\Advertising\Models\Creative;
use Illuminate\Database\Eloquent\Collection;

/**
 * Orchestrates advertising campaign lifecycle
 * Manages communication between Laravel (business logic) and Revive (delivery)
 */
class CampaignService
{
    public function __construct(private AdServerInterface $adServer)
    {}

    /**
     * Create a new campaign from an order
     */
    public function createCampaignFromOrder(AdOrder $order, array $campaignData): ?AdCampaign
    {
        // Validate order is in correct state
        if (!in_array($order->status, ['approved', 'pending_payment'])) {
            throw new \InvalidArgumentException('Order must be approved or pending payment');
        }

        // Create campaign in Laravel
        $campaign = AdCampaign::create([
            'advertiser_id' => $order->advertiser_id,
            'order_id' => $order->id,
            'name' => $campaignData['name'],
            'description' => $campaignData['description'] ?? null,
            'type' => $order->package->billing_type,
            'budget' => $order->total,
            'status' => 'draft',
        ]);

        return $campaign;
    }

    /**
     * Submit campaign for approval
     */
    public function submitForApproval(AdCampaign $campaign): bool
    {
        if ($campaign->status !== 'draft') {
            throw new \InvalidArgumentException('Only draft campaigns can be submitted');
        }

        // Validate campaign has creatives
        if ($campaign->creatives()->count() === 0) {
            throw new \InvalidArgumentException('Campaign must have at least one creative');
        }

        // Validate campaign has placements
        if ($campaign->placements()->count() === 0) {
            throw new \InvalidArgumentException('Campaign must have at least one placement');
        }

        return $campaign->update(['status' => 'pending_review']);
    }

    /**
     * Approve campaign and prepare for activation
     */
    public function approveCampaign(AdCampaign $campaign, int $adminUserId): bool
    {
        if ($campaign->status !== 'pending_review') {
            throw new \InvalidArgumentException('Only pending_review campaigns can be approved');
        }

        // Check if payment is complete
        $order = $campaign->order;
        if ($order && $order->invoice && !$order->invoice->isPaid()) {
            throw new \InvalidArgumentException('Campaign payment not complete');
        }

        // Approve in Laravel
        if (!$campaign->approve($adminUserId)) {
            return false;
        }

        // Create in Revive if not already created
        if (!$campaign->revive_campaign_id) {
            $this->syncCampaignToRevive($campaign);
        }

        return true;
    }

    /**
     * Activate campaign (start serving ads)
     */
    public function activateCampaign(AdCampaign $campaign): bool
    {
        if (!$campaign->canActivate()) {
            throw new \InvalidArgumentException("Campaign cannot be activated from status: {$campaign->status}");
        }

        // Sync to Revive if not already synced
        if (!$campaign->revive_campaign_id) {
            $this->syncCampaignToRevive($campaign);
        }

        // Activate in Revive
        if (!$this->adServer->activateCampaign($campaign->revive_campaign_id)) {
            throw new \RuntimeException('Failed to activate campaign in ad server');
        }

        // Activate in Laravel
        return $campaign->activate();
    }

    /**
     * Pause campaign
     */
    public function pauseCampaign(AdCampaign $campaign): bool
    {
        if (!$campaign->canPause()) {
            throw new \InvalidArgumentException('Campaign cannot be paused');
        }

        // Pause in Revive
        if ($campaign->revive_campaign_id) {
            $this->adServer->pauseCampaign($campaign->revive_campaign_id);
        }

        return $campaign->pause();
    }

    /**
     * Cancel campaign
     */
    public function cancelCampaign(AdCampaign $campaign): bool
    {
        if (!$campaign->canCancel()) {
            throw new \InvalidArgumentException('Campaign cannot be cancelled');
        }

        // Remove from Revive if necessary
        if ($campaign->revive_campaign_id) {
            $this->adServer->pauseCampaign($campaign->revive_campaign_id);
        }

        return $campaign->cancel();
    }

    /**
     * Sync campaign to Revive Adserver
     */
    public function syncCampaignToRevive(AdCampaign $campaign): bool
    {
        // If already synced, update instead
        if ($campaign->revive_campaign_id) {
            return $this->updateCampaignInRevive($campaign);
        }

        // Create advertiser in Revive if needed
        $reviveAdvertiserId = $this->ensureAdvertiserInRevive($campaign->advertiser);

        // Create campaign in Revive
        $reviveCampaignId = $this->adServer->createCampaign([
            'advertiser_id' => $reviveAdvertiserId,
            'campaign_name' => $campaign->name,
            'start_date' => $campaign->starts_at?->toDateString(),
            'end_date' => $campaign->ends_at?->toDateString(),
            'type' => $campaign->type,
            'budget' => $campaign->budget / 100, // convert cents to currency
            'priority' => 50,
        ]);

        if (!$reviveCampaignId) {
            \Log::error("Failed to create campaign in Revive for campaign {$campaign->id}");
            return false;
        }

        // Update campaign with Revive ID
        $campaign->update(['revive_campaign_id' => $reviveCampaignId]);

        // Sync creatives
        foreach ($campaign->creatives as $creative) {
            $this->syncCreativeToRevive($creative, $reviveCampaignId);
        }

        // Link to zones
        foreach ($campaign->placements as $placement) {
            $this->linkPlacementToRevive($placement, $reviveCampaignId);
        }

        return true;
    }

    /**
     * Update campaign in Revive
     */
    private function updateCampaignInRevive(AdCampaign $campaign): bool
    {
        return $this->adServer->updateCampaign($campaign->revive_campaign_id, [
            'campaign_name' => $campaign->name,
            'start_date' => $campaign->starts_at?->toDateString(),
            'end_date' => $campaign->ends_at?->toDateString(),
            'budget' => $campaign->budget / 100,
        ]);
    }

    /**
     * Sync creative/banner to Revive
     */
    private function syncCreativeToRevive(Creative $creative, int $reviveCampaignId): ?int
    {
        $bannerData = [
            'campaign_id' => $reviveCampaignId,
            'name' => $creative->name,
            'type' => $creative->format, // image, html5, native
            'width' => $creative->width,
            'height' => $creative->height,
        ];

        // Add content based on format
        if ($creative->format === 'image') {
            $bannerData['url'] = $creative->image_url;
        } elseif ($creative->format === 'html5') {
            $bannerData['html'] = $creative->html;
        }

        $bannerId = $this->adServer->createBanner($bannerData);

        if ($bannerId) {
            $creative->update(['external_banner_id' => $bannerId]);
        }

        return $bannerId;
    }

    /**
     * Link placement/zone to Revive campaign
     */
    private function linkPlacementToRevive(AdPlacement $placement, int $reviveCampaignId): bool
    {
        $placement->update([
            'revive_campaign_id' => $reviveCampaignId,
            'revive_zone_id' => $placement->zone->revive_zone_id,
        ]);

        return $this->adServer->linkCampaignToZone(
            $reviveCampaignId,
            $placement->zone->revive_zone_id
        );
    }

    /**
     * Ensure advertiser exists in Revive
     */
    private function ensureAdvertiserInRevive($advertiser): int
    {
        // If already synced, return ID
        if ($advertiser->external_advertiser_id) {
            return $advertiser->external_advertiser_id;
        }

        // Create in Revive
        $reviveAdvertiserId = $this->adServer->createAdvertiser([
            'name' => $advertiser->name,
            'contact_name' => $advertiser->contact_name,
            'email' => $advertiser->contact_email,
            'website' => $advertiser->website,
        ]);

        if ($reviveAdvertiserId) {
            $advertiser->update(['external_advertiser_id' => $reviveAdvertiserId]);
        }

        return $reviveAdvertiserId;
    }

    /**
     * Add placement to campaign
     */
    public function addPlacement(AdCampaign $campaign, int $zoneId, array $config = []): AdPlacement
    {
        $placement = AdPlacement::create([
            'campaign_id' => $campaign->id,
            'zone_id' => $zoneId,
            'priority' => $config['priority'] ?? 50,
            'frequency_cap' => $config['frequency_cap'] ?? null,
            'geotargeting' => $config['geotargeting'] ?? null,
            'device_targeting' => $config['device_targeting'] ?? null,
            'audience_targeting' => $config['audience_targeting'] ?? null,
        ]);

        // Sync to Revive if campaign is already synced
        if ($campaign->revive_campaign_id) {
            $this->linkPlacementToRevive($placement, $campaign->revive_campaign_id);
        }

        return $placement;
    }

    /**
     * Remove placement from campaign
     */
    public function removePlacement(AdPlacement $placement): bool
    {
        // Remove from Revive if synced
        if ($placement->campaign->revive_campaign_id && $placement->zone->revive_zone_id) {
            $this->adServer->unlinkCampaignFromZone(
                $placement->campaign->revive_campaign_id,
                $placement->zone->revive_zone_id
            );
        }

        return $placement->delete();
    }

    /**
     * Get campaign performance summary
     */
    public function getPerformanceSummary(AdCampaign $campaign): array
    {
        $metrics = $campaign->metrics()->get();

        return [
            'impressions' => $campaign->getTotalImpressions(),
            'clicks' => $campaign->getTotalClicks(),
            'conversions' => $metrics->sum('conversions'),
            'ctr' => $campaign->getCTR(),
            'budget' => $campaign->budget / 100,
            'budget_spent' => $campaign->budget_spent / 100,
            'budget_remaining' => $campaign->getRemainingBudget() / 100,
            'progress' => $campaign->budget > 0 
                ? ($campaign->budget_spent / $campaign->budget) * 100 
                : 0,
        ];
    }
}
