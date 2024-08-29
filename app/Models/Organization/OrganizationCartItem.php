<?php

namespace App\Models\Organization;

use App\Enums\Billing\Subscription\SubscriptionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrganizationCartItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_cart_id',
        'item_type',
        'item_id',
        'quantity',
        'frequency'
    ];

    protected $casts = [
        'frequency' => SubscriptionType::class
    ];

    /**
     * Get the item.
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}