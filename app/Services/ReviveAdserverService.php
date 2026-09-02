<?php

namespace App\Services;

use App\Contracts\Advertising\AdServerInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ReviveAdserverService implements AdServerInterface
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected string $apiVersion;
    protected ?string $sessionId = null;
    protected ?string $lastError = null;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.revive.url', 'http://localhost/revive'), '/');
        $this->username = config('services.revive.username', 'admin');
        $this->password = config('services.revive.password', 'password');
        $this->apiVersion = config('services.revive.api_version', '3.0');
    }

    public function authenticate(): bool
    {
        try {
            $this->sessionId = Cache::get(config('services.revive.session_cache_key', 'revive_session_id'));

            if ($this->sessionId) {
                $this->lastError = null;
                return true;
            }

            $response = $this->rpcCall('logon', [
                $this->username,
                $this->password,
                $this->apiVersion,
            ]);

            if (isset($response['faultCode'])) {
                $this->lastError = $response['faultString'] ?? 'Revive authentication failed';
                return false;
            }

            $this->sessionId = (string) $response;
            Cache::put(config('services.revive.session_cache_key', 'revive_session_id'), $this->sessionId, now()->addHours(24));
            $this->lastError = null;

            return true;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Revive authentication failed: ' . $e->getMessage());
            return false;
        }
    }

    public function logout(): bool
    {
        if (!$this->sessionId) {
            return true;
        }

        try {
            $this->rpcCall('logoff', [$this->sessionId]);
            Cache::forget(config('services.revive.session_cache_key', 'revive_session_id'));
            $this->sessionId = null;
            $this->lastError = null;
            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to logout from Revive: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function createZone(array $zoneData): ?int
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('addZone', [$this->sessionId, $zoneData]);
            return is_numeric($response) ? (int) $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to create zone in Revive: ' . $e->getMessage());
            return null;
        }
    }

    public function updateZone(int $zoneId, array $zoneData): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $zoneData['zoneId'] = $zoneId;
            $response = $this->rpcCall('modifyZone', [$this->sessionId, $zoneData]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to update zone {$zoneId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function deleteZone(int $zoneId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('deleteZone', [$this->sessionId, $zoneId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to delete zone {$zoneId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function getZoneDetails(int $zoneId): ?array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('getZone', [$this->sessionId, $zoneId]);
            return is_array($response) ? $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch zone {$zoneId} from Revive: " . $e->getMessage());
            return null;
        }
    }

    public function getZones(array $filters = []): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getZoneListByAdvertiserId', [$this->sessionId, 0]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to fetch zones from Revive: ' . $e->getMessage());
            return [];
        }
    }

    public function createAdvertiser(array $advertiserData): ?int
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('addAdvertiser', [$this->sessionId, $advertiserData]);
            return is_numeric($response) ? (int) $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to create advertiser in Revive: ' . $e->getMessage());
            return null;
        }
    }

    public function updateAdvertiser(int $advertiserId, array $data): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('modifyAdvertiser', [$this->sessionId, array_merge(['advertiserId' => $advertiserId], $data)]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to update advertiser {$advertiserId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function getAdvertiser(int $advertiserId): ?array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('getAdvertiser', [$this->sessionId, $advertiserId]);
            return is_array($response) ? $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch advertiser {$advertiserId} from Revive: " . $e->getMessage());
            return null;
        }
    }

    public function getAdvertisers(array $filters = []): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getAdvertiserListByAgencyId', [$this->sessionId, 0]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to fetch advertisers from Revive: ' . $e->getMessage());
            return [];
        }
    }

    public function createCampaign(array $campaignData): ?int
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('addCampaign', [$this->sessionId, $campaignData]);
            return is_numeric($response) ? (int) $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to create campaign in Revive: ' . $e->getMessage());
            return null;
        }
    }

    public function updateCampaign(int $campaignId, array $data): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('modifyCampaign', [$this->sessionId, array_merge(['campaignId' => $campaignId], $data)]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to update campaign {$campaignId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCampaign(int $campaignId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('deleteCampaign', [$this->sessionId, $campaignId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to delete campaign {$campaignId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function getCampaign(int $campaignId): ?array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('getCampaign', [$this->sessionId, $campaignId]);
            return is_array($response) ? $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch campaign {$campaignId} from Revive: " . $e->getMessage());
            return null;
        }
    }

    public function getCampaigns(array $filters = []): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getCampaignListByAgencyId', [$this->sessionId, 0]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to fetch campaigns from Revive: ' . $e->getMessage());
            return [];
        }
    }

    public function createBanner(array $bannerData): ?int
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('addBanner', [$this->sessionId, $bannerData]);
            return is_numeric($response) ? (int) $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('Failed to create banner in Revive: ' . $e->getMessage());
            return null;
        }
    }

    public function updateBanner(int $bannerId, array $data): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('modifyBanner', [$this->sessionId, array_merge(['bannerId' => $bannerId], $data)]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to update banner {$bannerId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBanner(int $bannerId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('deleteBanner', [$this->sessionId, $bannerId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to delete banner {$bannerId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function getBanner(int $bannerId): ?array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('getBanner', [$this->sessionId, $bannerId]);
            return is_array($response) ? $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch banner {$bannerId} from Revive: " . $e->getMessage());
            return null;
        }
    }

    public function getBanners(int $campaignId): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getBannerListByCampaignId', [$this->sessionId, $campaignId]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch banners for campaign {$campaignId} from Revive: " . $e->getMessage());
            return [];
        }
    }

    public function linkCampaignToZone(int $campaignId, int $zoneId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('linkCampaignToZone', [$this->sessionId, $campaignId, $zoneId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to link campaign {$campaignId} to zone {$zoneId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function unlinkCampaignFromZone(int $campaignId, int $zoneId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('unlinkCampaignFromZone', [$this->sessionId, $campaignId, $zoneId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to unlink campaign {$campaignId} from zone {$zoneId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    public function pauseCampaign(int $campaignId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('pauseCampaign', [$this->sessionId, $campaignId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to pause Revive campaign {$campaignId}: " . $e->getMessage());
            return false;
        }
    }

    public function activateCampaign(int $campaignId): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('activateCampaign', [$this->sessionId, $campaignId]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to activate Revive campaign {$campaignId}: " . $e->getMessage());
            return false;
        }
    }

    public function getCampaignStatistics(int $campaignId, ?string $startDate = null, ?string $endDate = null): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $startDate = $startDate ?? now()->subDays(30)->toDateString();
            $endDate = $endDate ?? now()->toDateString();
            $response = $this->rpcCall('getCampaignStatistics', [$this->sessionId, $campaignId, $startDate, $endDate]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch campaign stats for {$campaignId}: " . $e->getMessage());
            return [];
        }
    }

    public function getZoneStatistics(int $zoneId, ?string $startDate = null, ?string $endDate = null): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $startDate = $startDate ?? now()->subDays(30)->toDateString();
            $endDate = $endDate ?? now()->toDateString();
            $response = $this->rpcCall('getZoneStatistics', [$this->sessionId, $zoneId, $startDate, $endDate]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch stats for zone {$zoneId}: " . $e->getMessage());
            return [];
        }
    }

    public function getZoneStats(int $zoneId, ?string $startDate = null, ?string $endDate = null): array
    {
        return $this->getZoneStatistics($zoneId, $startDate, $endDate);
    }

    public function getCampaignStats(int $campaignId, ?string $startDate = null, ?string $endDate = null): array
    {
        return $this->getCampaignStatistics($campaignId, $startDate, $endDate);
    }

    public function getBannerStatistics(int $bannerId, ?string $startDate = null, ?string $endDate = null): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $startDate = $startDate ?? now()->subDays(30)->toDateString();
            $endDate = $endDate ?? now()->toDateString();
            $response = $this->rpcCall('getBannerStatistics', [$this->sessionId, $bannerId, $startDate, $endDate]);
            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch banner stats for {$bannerId}: " . $e->getMessage());
            return [];
        }
    }

    public function setCampaignDeliveryLimits(int $campaignId, array $limits): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $response = $this->rpcCall('setCampaignDeliveryLimits', [$this->sessionId, $campaignId, $limits]);
            return $response === true || $response === 1;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to set delivery limits for campaign {$campaignId}: " . $e->getMessage());
            return false;
        }
    }

    public function getZoneInvocationCode(int $zoneId): ?string
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('getZoneInvocationCode', [$this->sessionId, $zoneId]);
            return is_string($response) ? $response : null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error("Failed to fetch invocation code for zone {$zoneId}: " . $e->getMessage());
            return null;
        }
    }

    public function isConnected(): bool
    {
        return $this->sessionId !== null || $this->authenticate();
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    protected function rpcCall(string $method, array $params = []): mixed
    {
        $client = new \PhpXmlRpc\Client($this->baseUrl . '/api/v2/xmlrpc');

        $request = new \PhpXmlRpc\Request($method, array_map(
            static fn ($param) => \PhpXmlRpc\Helper\Charset::encodeEntities(
                \PhpXmlRpc\Encoder::encode($param)
            ),
            $params
        ));

        $response = $client->send($request);

        if (!$response->faultCode()) {
            $value = $response->value();

            if (is_object($value) && method_exists($value, 'phpval')) {
                return $value->phpval();
            }

            if (class_exists('\\PhpXmlRpc\\Decoder')) {
                return \PhpXmlRpc\Decoder::decode($value);
            }

            return $value;
        }

        throw new \RuntimeException('Revive API Error: ' . $response->faultString());
    }

    public function getZonesByAdvertiserId(int $advertiserId = 0): array
    {
        return $this->getZones();
    }

    public function getCampaignsByAdvertiserId(int $advertiserId = 0): array
    {
        return $this->getCampaigns();
    }
}
