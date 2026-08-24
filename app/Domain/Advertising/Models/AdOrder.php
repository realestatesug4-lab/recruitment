<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a purchase order from an advertiser
 * Links to AdPackage for pricing
 */
class AdOrder extends Model
{
    use HasFactory;

    protected $table = 'ad_orders';

    protected $fillable = [
        'advertiser_id',
        'package_id',
        'status',          // draft, pending_approval, approved, active, completed, cancelled
        'quantity',        // number of times to renew/quantity of impressions
        'subtotal',        // price before tax/discount
        'discount',        // discount amount
        'tax',
        'total',           // final amount due
        'currency',
        'notes',
        'requested_at',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'subtotal' => 'integer',
        'discount' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function package()
    {
        return $this->belongsTo(AdPackage::class);
    }

    public function campaign()
    {
        return $this->hasOne(AdCampaign::class, 'order_id');
    }

    public function invoice()
    {
        return $this->hasOne(AdInvoice::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(AdPayment::class, 'order_id');
    }
}
