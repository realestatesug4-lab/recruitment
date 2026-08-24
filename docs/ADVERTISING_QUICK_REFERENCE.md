# Cranelinks Advertising - Quick Reference

## For Developers

### Display an Ad
```blade
<x-ad-zone zone="home_top" />
```

### Display Ad with Context
```blade
<x-ad-zone zone="job_detail_sidebar" :context="['category' => 'tech']" />
```

### Campaign Workflow
```php
use App\Services\Advertising\CampaignService;

$service = app(CampaignService::class);

// Create from order
$campaign = $service->createCampaignFromOrder($order, [
    'name' => 'Q3 Recruitment Drive',
    'description' => 'Hire senior engineers',
]);

// Add creatives
$campaign->creatives()->create([
    'name' => 'Banner 300x250',
    'format' => 'image',
    'image_url' => 'https://...',
    'width' => 300,
    'height' => 250,
]);

// Add placements
$service->addPlacement($campaign, $zoneId = 1);
$service->addPlacement($campaign, $zoneId = 2);

// Submit for review
$service->submitForApproval($campaign);

// Admin approves (automatically syncs to Revive)
$service->approveCampaign($campaign, $adminUserId = 1);

// Activate
$service->activateCampaign($campaign);

// View performance
$summary = $service->getPerformanceSummary($campaign);
// → impressions, clicks, ctr, budget, progress, etc.
```

### Query Campaigns
```php
// Active campaigns
$active = AdCampaign::where('status', 'active')->get();

// By advertiser
$campaigns = $advertiser->campaigns()->where('status', 'active')->get();

// Statistics
$metrics = $campaign->metrics()->whereDate('date', '>=', now()->subDays(30))->get();
$totalImpressions = $campaign->getTotalImpressions();
$totalClicks = $campaign->getTotalClicks();
$ctr = $campaign->getCTR();
```

## For Admins (Filament)

1. **Advertising → Zones**
   - View all placement slots
   - See Revive zone IDs
   - Enable/disable zones
   - Edit sizes and positions

2. **Advertising → Packages**
   - Create pricing tiers
   - Set impression limits
   - Define allowed zones
   - Manage discounts

3. **Advertising → Advertisers**
   - View advertiser accounts
   - Manage contact info
   - View all campaigns
   - View total spend

4. **Advertising → Campaigns**
   - View all campaigns
   - Filter by status
   - Approve/reject pending
   - View performance metrics
   - Pause/activate campaigns

5. **Advertising → Orders**
   - View purchase orders
   - Process payments
   - Generate invoices
   - Trigger campaign creation

6. **Advertising → Reports**
   - Campaign performance
   - Zone performance
   - Revenue dashboard
   - Advertiser summaries

## Database Queries

### Get ad invocation code
```php
$zone = AdZone::where('slug', 'home_top')->first();
// Use: $zone->revive_zone_id
```

### Campaign performance
```php
$campaign = AdCampaign::find($id);
$campaign->getTotalImpressions();
$campaign->getTotalClicks();
$campaign->getCTR();
$campaign->getRemainingBudget();
```

### Zone statistics (last 30 days)
```php
$zone = AdZone::find($id);
$stats = $zone->statistics()
    ->where('date', '>=', now()->subDays(30))
    ->sum('impressions');
```

### Top performing campaigns
```php
$topCampaigns = AdCampaignMetrics::select('campaign_id')
    ->selectRaw('SUM(impressions) as total_impressions')
    ->selectRaw('SUM(clicks) as total_clicks')
    ->where('date', '>=', now()->subDays(7))
    ->groupBy('campaign_id')
    ->orderByDesc('total_clicks')
    ->limit(10)
    ->get();
```

## Artisan Commands

### Sync zones from Revive
```bash
php artisan revive:sync-zones
```

### Sync statistics from Revive
```bash
php artisan revive:sync-stats --days=30
```

### Activate scheduled campaigns
```bash
php artisan advertising:activate-scheduled
```

### Deactivate completed campaigns
```bash
php artisan advertising:complete-expired
```

## Configuration

### `.env`
```env
REVIVE_ENABLED=true
REVIVE_URL=https://ads.cranelinks.com
REVIVE_USERNAME=admin
REVIVE_PASSWORD=secure_password

ADVERTISING_ENABLED=true
ADVERTISING_SELF_SERVICE=false
```

### Feature Flags
```php
// config/advertising.php
return [
    'enabled' => env('ADVERTISING_ENABLED', false),
    'self_service' => env('ADVERTISING_SELF_SERVICE', false),
    'revive' => [
        'enabled' => env('REVIVE_ENABLED', false),
        'url' => env('REVIVE_URL', 'http://localhost/revive'),
        'username' => env('REVIVE_USERNAME', 'admin'),
        'password' => env('REVIVE_PASSWORD', 'password'),
    ],
    'zones' => [
        'home_top' => ['width' => 728, 'height' => 90, 'device' => 'desktop'],
        'home_sidebar' => ['width' => 300, 'height' => 250, 'device' => 'desktop'],
        // ... more zones
    ],
];
```

## Model Relationships

```php
// Advertiser has many campaigns
$advertiser->campaigns();  // AdCampaign
$advertiser->orders();     // AdOrder

// Campaign has many components
$campaign->creatives();    // Creative (banners)
$campaign->placements();   // AdPlacement (zones)
$campaign->metrics();      // AdCampaignMetrics (stats)

// Zone has many placements
$zone->placements();       // AdPlacement
$zone->statistics();       // AdCampaignMetrics

// Order has campaign and invoice
$order->campaign();        // AdCampaign
$order->invoice();         // AdInvoice
$order->payment();         // AdPayment
```

## Integration Points

### Payment Processing
```php
// After payment succeeds:
$order->update(['status' => 'approved']);
$service->approveCampaign($campaign, $adminId = 1);
// Campaign syncs to Revive automatically
```

### Email Notifications
```php
// Campaign approved
AdvertiserNotification::campaignApproved($campaign);

// Campaign activated
AdvertiserNotification::campaignActive($campaign);

// Campaign completed
AdvertiserNotification::campaignCompleted($campaign);
```

### Webhook from Revive (Future)
```php
// Revive sends stats webhook
Route::post('/webhooks/revive/stats', function (Request $request) {
    // Update AdCampaignMetrics
    // Trigger alerts if thresholds reached
});
```

## Debugging

### Check campaign sync status
```php
$campaign = AdCampaign::find($id);
if ($campaign->revive_campaign_id) {
    \Log::info("Campaign synced to Revive: {$campaign->revive_campaign_id}");
} else {
    \Log::warning("Campaign NOT synced to Revive");
}
```

### Verify zone mapping
```php
$zone = AdZone::find($id);
if ($zone->revive_zone_id) {
    \Log::info("Zone mapped: {$zone->slug} → Revive zone {$zone->revive_zone_id}");
} else {
    \Log::warning("Zone NOT mapped to Revive");
}
```

### Test Revive connection
```php
$revive = app(\App\Services\ReviveAdserverService::class);
if ($revive->authenticate()) {
    \Log::info("Revive connection successful");
} else {
    \Log::error("Revive connection failed");
}
```
