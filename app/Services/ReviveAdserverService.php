<?php

namespace App\Services;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ReviveAdserverService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected ?string $sessionId = null;

    public function __construct()
    {
        $this->baseUrl = config('services.revive.url', 'http://localhost/revive');
        $this->username = config('services.revive.username', 'admin');
        $this->password = config('services.revive.password', 'password');
    }

    /**
     * Authenticate with Revive XML-RPC API
     */
    public function authenticate(): bool
    {
        try {
            $this->sessionId = Cache::get('revive_session_id');

            if ($this->sessionId) {
                return true;
            }

            $response = $this->rpcCall('logon', [
                $this->username,
                $this->password,
                '3.0', // API version
            ]);

            if (isset($response['faultCode'])) {
                return false;
            }

            $this->sessionId = $response;
            Cache::put('revive_session_id', $this->sessionId, now()->addHours(24));

            return true;
        } catch (\Exception $e) {
            \Log::error('Revive authentication failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all zones from Revive
     */
    public function getZones(): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getZoneListByAdvertiserId', [
                $this->sessionId,
                0, // 0 = all zones
            ]);

            return is_array($response) ? $response : [];
        } catch (\Exception $e) {
            \Log::error('Failed to fetch zones from Revive: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get zone details from Revive
     */
    public function getZoneDetails(int $zoneId): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getZone', [
                $this->sessionId,
                $zoneId,
            ]);

            return is_array($response) ? $response : [];
        } catch (\Exception $e) {
            \Log::error("Failed to fetch zone {$zoneId} from Revive: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get ad impressions/stats for a zone
     */
    public function getZoneStats(int $zoneId, $startDate = null, $endDate = null): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $startDate = $startDate ?? now()->subDays(30)->toDateString();
            $endDate = $endDate ?? now()->toDateString();

            $response = $this->rpcCall('getZoneStatistics', [
                $this->sessionId,
                $zoneId,
                $startDate,
                $endDate,
            ]);

            return is_array($response) ? $response : [];
        } catch (\Exception $e) {
            \Log::error("Failed to fetch stats for zone {$zoneId} from Revive: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a new zone in Revive
     */
    public function createZone(array $zoneData): ?int
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return null;
        }

        try {
            $response = $this->rpcCall('addZone', [
                $this->sessionId,
                $zoneData,
            ]);

            return is_numeric($response) ? (int)$response : null;
        } catch (\Exception $e) {
            \Log::error('Failed to create zone in Revive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update zone in Revive
     */
    public function updateZone(int $zoneId, array $zoneData): bool
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return false;
        }

        try {
            $zoneData['zoneId'] = $zoneId;
            $response = $this->rpcCall('modifyZone', [
                $this->sessionId,
                $zoneData,
            ]);

            return $response === true || $response === 1;
        } catch (\Exception $e) {
            \Log::error("Failed to update zone {$zoneId} in Revive: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all campaigns from Revive
     */
    public function getCampaigns(): array
    {
        if (!$this->sessionId && !$this->authenticate()) {
            return [];
        }

        try {
            $response = $this->rpcCall('getAdvertiserListByAgencyId', [
                $this->sessionId,
                0, // 0 = all campaigns
            ]);

            return is_array($response) ? $response : [];
        } catch (\Exception $e) {
            \Log::error('Failed to fetch campaigns from Revive: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Make XML-RPC call to Revive API
     */
    protected function rpcCall(string $method, array $params = []): mixed
    {
        $client = new \PhpXmlRpc\Client($this->baseUrl . '/api/v2/xmlrpc');

        $request = new \PhpXmlRpc\Request($method, array_map(
            fn($param) => \PhpXmlRpc\Helper\Charset::encodeEntities(
                \PhpXmlRpc\Encoder::encode($param)
            ),
            $params
        ));

        $response = $client->send($request);

        if (!$response->faultCode()) {
            return \PhpXmlRpc\Decoder::decode($response->value());
        }

        throw new \Exception("Revive API Error: {$response->faultString()}");
    }

    /**
     * Logout from Revive
     */
    public function logout(): bool
    {
        if (!$this->sessionId) {
            return true;
        }

        try {
            $this->rpcCall('logoff', [$this->sessionId]);
            Cache::forget('revive_session_id');
            $this->sessionId = null;
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to logout from Revive: ' . $e->getMessage());
            return false;
        }
    }
}
