<?php

namespace App\Contracts\Advertising;

interface AdServerInterface
{
    /**
     * Authenticate with the ad server
     */
    public function authenticate(): bool;

    /**
     * Logout from the ad server
     */
    public function logout(): bool;

    /**
     * Create a zone (ad placement slot)
     */
    public function createZone(array $zoneData): ?int;

    /**
     * Update a zone
     */
    public function updateZone(int $zoneId, array $zoneData): bool;

    /**
     * Delete a zone
     */
    public function deleteZone(int $zoneId): bool;

    /**
     * Get zone details
     */
    public function getZoneDetails(int $zoneId): ?array;

    /**
     * Get all zones
     */
    public function getZones(array $filters = []): array;

    /**
     * Create an advertiser account
     */
    public function createAdvertiser(array $advertiserData): ?int;

    /**
     * Update an advertiser
     */
    public function updateAdvertiser(int $advertiserId, array $data): bool;

    /**
     * Get advertiser details
     */
    public function getAdvertiser(int $advertiserId): ?array;

    /**
     * Get all advertisers
     */
    public function getAdvertisers(array $filters = []): array;

    /**
     * Create a campaign
     */
    public function createCampaign(array $campaignData): ?int;

    /**
     * Update a campaign
     */
    public function updateCampaign(int $campaignId, array $data): bool;

    /**
     * Delete a campaign
     */
    public function deleteCampaign(int $campaignId): bool;

    /**
     * Get campaign details
     */
    public function getCampaign(int $campaignId): ?array;

    /**
     * Get all campaigns
     */
    public function getCampaigns(array $filters = []): array;

    /**
     * Create a banner/creative
     */
    public function createBanner(array $bannerData): ?int;

    /**
     * Update a banner
     */
    public function updateBanner(int $bannerId, array $data): bool;

    /**
     * Delete a banner
     */
    public function deleteBanner(int $bannerId): bool;

    /**
     * Get banner details
     */
    public function getBanner(int $bannerId): ?array;

    /**
     * Get all banners for a campaign
     */
    public function getBanners(int $campaignId): array;

    /**
     * Link campaign to zone (add zone to campaign delivery)
     */
    public function linkCampaignToZone(int $campaignId, int $zoneId): bool;

    /**
     * Unlink campaign from zone
     */
    public function unlinkCampaignFromZone(int $campaignId, int $zoneId): bool;

    /**
     * Pause a campaign
     */
    public function pauseCampaign(int $campaignId): bool;

    /**
     * Resume/activate a campaign
     */
    public function activateCampaign(int $campaignId): bool;

    /**
     * Get campaign statistics
     */
    public function getCampaignStatistics(
        int $campaignId,
        ?string $startDate = null,
        ?string $endDate = null
    ): array;

    /**
     * Get zone statistics
     */
    public function getZoneStatistics(
        int $zoneId,
        ?string $startDate = null,
        ?string $endDate = null
    ): array;

    /**
     * Get banner statistics
     */
    public function getBannerStatistics(
        int $bannerId,
        ?string $startDate = null,
        ?string $endDate = null
    ): array;

    /**
     * Set campaign delivery limits (impressions, budget, schedule)
     */
    public function setCampaignDeliveryLimits(
        int $campaignId,
        array $limits
    ): bool;

    /**
     * Get available invocation code for a zone
     */
    public function getZoneInvocationCode(int $zoneId): ?string;

    /**
     * Check connection status
     */
    public function isConnected(): bool;

    /**
     * Get last error message
     */
    public function getLastError(): ?string;
}
