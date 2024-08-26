<?php

namespace App\Models\Administration;

use App\Models\Organization\Subscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Module extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'module_category_id',
        'name',
        'description',
        'url',
        'icon',
        'banner',
        'subscription_type',
        'price',
        'active'
    ];

    protected $casts = [
        'price' => 'float',
        'active' => 'boolean'
    ];

    protected $appends = ['is_subscribed'];

    /**
     * Get the module category that owns the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleCategory(): BelongsTo
    {
        return $this->belongsTo(ModuleCategory::class);
    }

    /**
     * Get all of the subscriptions for the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope a query to only include active Modules.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Accessor for subscription_type to ensure it's always returned as ucfirst.
     *
     * @return string
     */
    public function getSubscriptionTypeAttribute(): string
    {
        return ucfirst($this->attributes['subscription_type']);
    }

    /**
     * Accessor to determine if the current user's organization is subscribed.
     */
    public function getIsSubscribedAttribute(): bool
    {
        if (Auth::user()) {
            $organizationId = Auth::user()->organization_id;

            if (!$organizationId) {
                return false;
            }

            return $this->subscriptions()
                ->whereDate('next_billing_date', '>', Carbon::now())
                ->exists();
        }
        return false;
    }
}