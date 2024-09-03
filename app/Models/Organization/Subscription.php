<?php

namespace App\Models\Organization;

use App\Enums\Billing\Subscription\SubscriptionType;
use App\Models\Administration\Module;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory, HasUuids, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'module_id',
        'subscription_type',
        'price',
        'next_billing_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'subscription_type' => SubscriptionType::class,
        'price' => 'float',
        'next_billing_date' => 'datetime',
    ];

    /**
     * Get the module that owns the Subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}