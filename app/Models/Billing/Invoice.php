<?php

namespace App\Models\Billing;

use App\Enums\Billing\Payments\InvoiceStatus;
use App\Enums\Billing\Subscription\SubscriptionType;
use App\Models\Administration\Module;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'module_id',
        'subscription_type',
        'amount',
        'due_date',
        'status',
        'issued_at',
        'paid_at'
    ];

    protected $casts = [
        'subscription_type' => SubscriptionType::class,
        'amount' => 'float',
        'due_date' => 'date',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'status' => InvoiceStatus::class
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Boot method to automatically set issued_at when creating an invoice.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->issued_at = Carbon::now();
        });
    }

    /**
     * Get the module that owns the Invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Mark the invoice as paid.
     *
     * @return bool
     */
    public function markAsPaid(): bool
    {
        $this->status = InvoiceStatus::PAID;
        $this->paid_at = Carbon::now();
        return $this->save();
    }

    /**
     * Check if the invoice is overdue.
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== InvoiceStatus::PAID;
    }

    /**
     * Calculate the total amount of the invoice.
     *
     * @return float
     */
    public function totalAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set the invoice status as overdue if applicable.
     */
    public function checkAndMarkOverdue(): void
    {
        if ($this->isOverdue() && $this->status !== InvoiceStatus::OVERDUE) {
            $this->status = InvoiceStatus::OVERDUE;
            $this->save();
        }
    }
}