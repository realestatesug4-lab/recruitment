<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Billing invoice for advertising order
 */
class AdInvoice extends Model
{
    use HasFactory;

    protected $table = 'ad_invoices';

    protected $fillable = [
        'order_id',
        'invoice_number',
        'status',          // draft, sent, viewed, paid, partially_paid, overdue, cancelled
        'issue_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'amount_paid',
        'currency',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
        'amount_paid' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(AdOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(AdPayment::class, 'invoice_id');
    }

    public function getAmountRemaining(): int
    {
        return $this->total - $this->amount_paid;
    }

    public function isPaid(): bool
    {
        return $this->amount_paid >= $this->total;
    }
}

/**
 * Payment record for an advertising invoice
 */
class AdPayment extends Model
{
    use HasFactory;

    protected $table = 'ad_payments';

    protected $fillable = [
        'order_id',
        'invoice_id',
        'amount',
        'currency',
        'payment_method',  // credit_card, bank_transfer, momo, airtel_money, etc
        'transaction_id',
        'status',          // pending, completed, failed, refunded
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(AdOrder::class);
    }

    public function invoice()
    {
        return $this->belongsTo(AdInvoice::class);
    }
}
