<?php

namespace App\Models\Administration;

use App\Enums\Admin\Organization\OrganizationCategory;
use App\Enums\Admin\Organization\OrganizationType;
use App\Models\User;
use App\Traits\Payments\Billable;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Organization extends Model
{
    use HasFactory, HasUuids, SoftDeletes, Notifiable, Billable, AssociatedToUser;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'name',
        'description',
        'email',
        'email_verified_at',
        'phone',
        'phone_verified_at',
        'website',
        'address',
        'logo',
        'verified',
        'active',
    ];

    protected $casts = [
        'type' => OrganizationType::class,
        'category' => OrganizationCategory::class,
        'verified' => 'boolean',
        'active' => 'boolean'
    ];

    /**
     * Get all of the users for the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all of the structure types for the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function structureTypes(): HasMany
    {
        return $this->hasMany(StructureType::class);
    }

    /**
     * Get all of the structures for the Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    /**
     * Scope a query to only include verified Organizations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('verified', true);
    }

    /**
     * Scope a query to only include active Organizations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}