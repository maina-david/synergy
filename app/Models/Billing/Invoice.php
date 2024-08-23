<?php

namespace App\Models\Billing;

use App\Enums\Billing\Payments\InvoiceStatus;
use App\Enums\Billing\Subscription\SubscriptionType;
use App\Models\Administration\Module;
use App\Models\Administration\Organization;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'module_id',
        'subscription_type',
        'amount',
        'due_date',
        'status'
    ];

    protected $casts = [
        'subscription_type' => SubscriptionType::class,
        'amount' => 'float',
        'due_date' => 'date',
        'status' => InvoiceStatus::class
    ];

    /**
     * Get the organization that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the module that owns the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
