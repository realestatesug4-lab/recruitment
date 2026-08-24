# Cranelinks Advertising Platform - Complete Architecture

## Executive Summary

Cranelinks implements a **two-tier advertising architecture**:

1. **Laravel/Filament** - Owns all business logic, commerce, billing, approvals, reporting
2. **Revive Adserver** - Owns ad delivery, targeting, rotation, frequency capping, impressions/clicks tracking

This separation of concerns enables scaling, flexibility, and professional ad operations without reimplementing ad-serving technology.

---

## 1. Core Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    CRANELINKS (Laravel)                      │
│  Business Logic │ Commerce │ Billing │ Approvals │ Reporting │
└──────────────┬────────────────────────────────────────────────┘
               │
      ┌────────▼─────────────────────────────┐
      │  CampaignService (Orchestration)     │
      │  - Manages lifecycle                 │
      │  - Syncs to Revive                   │
      │  - Handles approvals                 │
      └────────┬──────────────────────────────┘
               │
      ┌────────▼─────────────────────────────┐
      │  AdServerInterface (Abstraction)     │
      │  - Abstract ad server contract       │
      │  - Flexible implementation           │
      └────────┬──────────────────────────────┘
               │
      ┌────────▼─────────────────────────────────┐
      │  ReviveAdserverService (Implementation)  │
      │  XML-RPC API calls to Revive            │
      └────────┬──────────────────────────────────┘
               │
      ┌────────▼─────────────────────────────────┐
      │    ads.cranelinks.com                    │
      │    Revive Adserver (Self-Hosted)         │
      │    - Campaign management                 │
      │    - Banner rotation                     │
      │    - Zone targeting                      │
      │    - Impression/click tracking           │
      │    - Statistics aggregation              │
      └────────────────────────────────────────────┘
```

---

## 2. Data Flow

### Campaign Creation → Activation

```
Advertiser
    ↓
AdOrder (purchase package)
    ↓
Payment → AdInvoice → AdPayment
    ↓
AdCampaign (draft)
    ↓
Add Creatives (banners)
    ↓
Add Placements (zones)
    ↓
Submit for review
    ↓
Admin approval (→ sync to Revive)
    ↓
CampaignService::syncCampaignToRevive()
    ├─→ Create advertiser in Revive
    ├─→ Create campaign in Revive
    ├─→ Create banners in Revive
    └─→ Link campaign → zones
    ↓
Campaign approved
    ↓
Campaign activated
    ↓
Revive begins serving ads
```

### Ad Serving

```
Website Visitor
    ↓
Request page (e.g., /jobs/123)
    ↓
Laravel renders template
    ↓
<x-ad-zone zone="job_detail_sidebar" :context="[...]" />
    ↓
Component generates Revive invocation code
    ↓
Browser executes JavaScript
    ↓
Revive zone call:
    /ads/zone/12/banner.js
    ↓
Revive:
  - Determine active campaigns for zone
  - Check targeting (geolocation, device, audience)
  - Select banner (rotation/weighting)
  - Track impression
  ↓
Return banner to browser
    ↓
Display advertisement
```

### Statistics Aggregation

```
Revive (real-time)
    │
    ├─ impression tracked
    ├─ click tracked
    └─ conversion tracked
    ↓
Daily (e.g., 1 AM)
    ↓
SyncReviveStats command
    ↓
Revive API: getStatistics()
    ↓
AdCampaignMetrics (insert/update)
    ├─ impressions
    ├─ clicks
    ├─ conversions
    ├─ CTR
    ├─ eCPM
    └─ revenue
    ↓
Filament dashboard queries AdCampaignMetrics
    ↓
Admin/Advertiser views reports
```

---

## 3. Database Schema

### Core Tables

| Table | Purpose | Sync with Revive |
|-------|---------|------------------|
| `ad_zones` | Ad placement slots | Zone ID stored |
| `ad_packages` | Pricing tiers | No |
| `ad_orders` | Purchase orders | No |
| `ad_invoices` | Billing documents | No |
| `ad_payments` | Payment records | No |
| `ad_campaigns` | Campaign lifecycle | Campaign ID + sync flag |
| `creatives` | Banner/ad content | Banner IDs stored |
| `ad_placements` | Campaign→Zone links | Both Revive IDs stored |
| `ad_campaign_metrics` | Daily statistics | Synced from Revive |

### Key Fields

```
ad_zones.revive_zone_id          // Revive zone identifier
ad_campaigns.revive_campaign_id   // Revive campaign identifier
creatives.external_banner_id      // Revive banner identifier
ad_placements.revive_campaign_id  // Which Revive campaign
ad_placements.revive_zone_id      // Which Revive zone
```

---

## 4. Campaign Lifecycle States

```
DRAFT
  └─► PENDING_PAYMENT (if using pre-payment)
        └─► PENDING_REVIEW (awaiting admin approval)
              ├─► APPROVED (approved, ready to activate)
              │   └─► SCHEDULED (scheduled for future)
              │       └─► ACTIVE (serving ads)
              │           └─► COMPLETED (time expired)
              │           └─► PAUSED (temporarily stopped)
              └─► REJECTED (admin rejected)
              
CANCELLED (can cancel from any state)
```

### State Transitions

```php
// Draft → Submit for Review
$service->submitForApproval($campaign);

// Pending Review → Approved
$service->approveCampaign($campaign, $adminUserId);
// Automatically syncs to Revive

// Approved → Active
$service->activateCampaign($campaign);
// Revive begins serving

// Active → Paused
$service->pauseCampaign($campaign);
// Revive stops serving

// Any → Cancelled
$service->cancelCampaign($campaign);
// Removes from Revive
```

---

## 5. Key Abstractions

### AdServerInterface
Defines contract that any ad server must implement:

```php
interface AdServerInterface {
    // Authentication
    authenticate(): bool;
    logout(): bool;
    
    // Zone management
    createZone(): ?int;
    updateZone(): bool;
    deleteZone(): bool;
    getZoneStatistics(): array;
    getZoneInvocationCode(): ?string;
    
    // Campaign management
    createCampaign(): ?int;
    updateCampaign(): bool;
    pauseCampaign(): bool;
    activateCampaign(): bool;
    
    // Banner management
    createBanner(): ?int;
    updateBanner(): bool;
    deleteBanner(): bool;
    
    // Linking
    linkCampaignToZone(): bool;
    unlinkCampaignFromZone(): bool;
    
    // Reporting
    getCampaignStatistics(): array;
    getZoneStatistics(): array;
    getBannerStatistics(): array;
}
```

**Benefit**: Swap implementations without changing business logic:
- `ReviveAdserverService` (current)
- `FakeAdServer` (testing)
- `GoogleAdExchange` (future)
- `AppNexus` (future)

### CampaignService
Orchestrates campaign lifecycle:

```php
class CampaignService {
    public function createCampaignFromOrder(AdOrder $order): AdCampaign;
    public function submitForApproval(AdCampaign $campaign): bool;
    public function approveCampaign(AdCampaign $campaign, int $adminId): bool;
    public function activateCampaign(AdCampaign $campaign): bool;
    public function pauseCampaign(AdCampaign $campaign): bool;
    public function cancelCampaign(AdCampaign $campaign): bool;
    public function syncCampaignToRevive(AdCampaign $campaign): bool;
    public function addPlacement(AdCampaign $campaign, int $zoneId): AdPlacement;
    public function getPerformanceSummary(AdCampaign $campaign): array;
}
```

---

## 6. Initial Ad Inventory (Phase 1)

| Zone Slug | Name | Size | Page | Device | Revive ID |
|-----------|------|------|------|--------|-----------|
| `home_top` | Homepage Top Banner | 728×90 | Homepage | Desktop | TBD |
| `home_sidebar` | Homepage Sidebar | 300×250 | Homepage | Desktop | TBD |
| `jobs_top` | Job Listings Top | 728×90 | Job List | Desktop | TBD |
| `jobs_sidebar` | Job Listings Sidebar | 300×250 | Job List | Desktop | TBD |
| `job_detail_top` | Job Detail Top | 728×90 | Job Detail | Desktop | TBD |
| `job_detail_sidebar` | Job Detail Sidebar | 300×250 | Job Detail | Desktop | TBD |
| `job_inline` | Inline Between Jobs | 600×250 | Job List | Desktop | TBD |
| `employer_banner` | Employer Profile | 728×90 | Employer | Desktop | TBD |
| `mobile_top` | Mobile Top | 320×100 | Any | Mobile | TBD |
| `mobile_inline` | Mobile Inline | 320×100 | Any | Mobile | TBD |

---

## 7. Sample Advertising Packages (Phase 1)

### Starter Package
- **Price**: 150,000 UGX
- **Impressions**: 50,000
- **Duration**: 30 days
- **Zones**: Any 2 zones
- **Format**: Image + HTML5

### Professional Package
- **Price**: 500,000 UGX
- **Impressions**: 250,000
- **Duration**: 60 days
- **Zones**: Any 5 zones
- **Format**: Image + HTML5 + Native

### Enterprise Package
- **Price**: 1,500,000 UGX
- **Impressions**: Unlimited
- **Duration**: 90 days
- **Zones**: All zones
- **Format**: All formats + Custom

---

## 8. Usage in Blade Templates

### Simple Usage
```blade
<!-- Homepage hero section ad -->
<x-ad-zone zone="home_top" />

<!-- With context for targeting -->
<x-ad-zone zone="job_detail_sidebar" 
    :context="[
        'job_category' => $job->category->slug,
        'job_level' => $job->level,
        'job_location' => $job->location->slug,
        'industry' => $job->industry->slug,
    ]" />

<!-- Mobile ad -->
@if(request()->header('User-Agent') && str_contains(request()->header('User-Agent'), 'Mobile'))
    <x-ad-zone zone="mobile_top" />
@else
    <x-ad-zone zone="home_top" />
@endif
```

### Component Output
```html
<div class="ad-zone ad-zone-home_top" 
     data-zone-id="1"
     data-zone-slug="home_top"
     data-zone-width="728"
     data-zone-height="90">
    <!-- Revive invocation code -->
    <script>...</script>
</div>
```

---

## 9. Admin Dashboard (Filament)

```
Advertising
├── Zones
│   ├── List (with Revive zone IDs)
│   ├── Create
│   └── Edit
├── Packages
│   ├── List
│   ├── Create
│   └── Edit
├── Advertisers
│   ├── List
│   ├── Create
│   └── Edit (approve/reject)
├── Campaigns
│   ├── List (filterable by status)
│   ├── Create
│   ├── Edit
│   ├── Approve
│   ├── Activate
│   ├── Pause
│   └── View Performance
├── Creatives
│   ├── List
│   ├── Create
│   └── Edit
├── Orders
│   ├── List
│   ├── View
│   └── Process Payment
├── Invoices
│   ├── List
│   ├── View/Print
│   └── Send
└── Reports
    ├── Campaign Performance
    ├── Zone Performance
    ├── Advertiser Summary
    └── Revenue Dashboard
```

---

## 10. Implementation Roadmap

### Phase 1: Infrastructure ✅
- ✅ AdServerInterface contract
- ✅ Models (AdZone, AdPackage, AdCampaign, etc.)
- ✅ Migrations
- ✅ CampaignService
- ✅ AdZone Blade component
- ⏳ Seed initial zones
- ⏳ Filament admin panel

### Phase 2: Manual Campaigns
- Admin creates advertiser account
- Admin uploads creatives
- Admin links to zones
- Revive serves ads
- Track statistics

### Phase 3: Self-Service Orders
- Advertiser dashboard
- Package selection
- Payment processing
- Auto-approval (after payment)
- Campaign management

### Phase 4: Advertiser Portal
- `/advertise` dashboard
- Campaign creation wizard
- Creative upload
- Zone selection
- Targeting options
- Real-time reporting

### Phase 5: Advanced Features
- Geotargeting by region/city
- Audience segments
- Frequency capping
- A/B testing
- Conversion tracking
- Programmatic buying

---

## 11. Deployment Configuration

### Environment Variables
```env
# Revive Configuration
REVIVE_ENABLED=true
REVIVE_URL=https://ads.cranelinks.com
REVIVE_USERNAME=admin
REVIVE_PASSWORD=secure_password
REVIVE_API_VERSION=3.0

# Advertising Feature Flags
ADVERTISING_ENABLED=true
ADVERTISING_SELF_SERVICE=false  # Phase 3+
ADVERTISING_GEOTARGETING=false  # Phase 5
```

### Deployment Architecture
```
                         cranelinks.com
                         (Django/Next/Laravel)
                              │
                ┌─────────────┼──────────────┐
                │             │              │
            PostgreSQL     Redis        Storage
                │
                ▼
        Advertising Service
                │
                ▼
        ads.cranelinks.com
         (Revive Adserver)
              │
              ├─ Advertiser accounts
              ├─ Campaign management
              ├─ Banner delivery
              ├─ Statistics collection
              └─ Zone configuration
```

---

## 12. Testing Strategy

### Unit Tests
```php
// Test campaign state transitions
CampaignTest::testDraftToApproved()
CampaignTest::testApprovedToActive()
CampaignTest::testCannotActivateFromDraft()

// Test service logic
CampaignServiceTest::testSyncToRevive()
CampaignServiceTest::testAddPlacement()
CampaignServiceTest::testPerformanceSummary()
```

### Integration Tests
```php
// Test full flow
IntegrationTest::testCampaignLifecycle()
    // Create campaign
    // Submit for approval
    // Approve
    // Activate
    // Verify Revive sync

IntegrationTest::testStatisticsSyncFromRevive()
    // Create campaign
    // Sync metrics from Revive
    // Verify in database
```

### Fake Ad Server
```php
// For testing without Revive
class FakeAdServer implements AdServerInterface {
    public function authenticate(): bool { return true; }
    public function createCampaign(): ?int { return rand(1000, 9999); }
    public function createBanner(): ?int { return rand(1000, 9999); }
    // ... etc
}

// Use in tests
$service = new CampaignService(new FakeAdServer());
```

---

## 13. Key Implementation Notes

1. **No Advertiser API Access** - Advertisers don't have Revive admin accounts
2. **Laravel Owns Commerce** - All billing/payment in Laravel, not Revive
3. **Revive Owns Delivery** - All ad-serving logic in Revive, not Laravel
4. **Clear Separation** - AdZone component abstracts Revive implementation
5. **Async Statistics** - Pull from Revive daily, cache in Laravel
6. **Flexible Testing** - AdServerInterface enables easy mocking
7. **Performance** - Cache zone invocation codes, don't query Revive per request
8. **Audit Trail** - Log all state transitions, approvals, syncs

---

## 14. Next Steps

1. Run migrations:
   ```bash
   php artisan migrate
   ```

2. Seed initial zones:
   ```bash
   php artisan db:seed --class=AdZoneSeeder
   ```

3. Seed sample packages:
   ```bash
   php artisan db:seed --class=AdPackageSeeder
   ```

4. Test component:
   ```blade
   <x-ad-zone zone="home_top" />
   ```

5. Build Filament admin:
   - Create AdvertisingGroupResource
   - Create AdZoneResource
   - Create AdPackageResource
   - Create AdCampaignResource
   - Create AdOrderResource

