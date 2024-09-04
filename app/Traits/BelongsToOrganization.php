<?php

namespace App\Traits;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToOrganization
{
    /**
     * Boot the trait to add model event listeners and global scope.
     *
     * @return void
     */
    public static function bootBelongsToOrganization(): void
    {
        static::creating(function (Model $model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });

        static::updating(function (Model $model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });

        static::saving(function (Model $model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });
    }

    /**
     * Get the organization that owns the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Check if the model belongs to an organization.
     *
     * @return bool
     */
    public function belongsToOrganization(): bool
    {
        return !is_null($this->organization_id);
    }

    /**
     * Scope a query to only include models for a specific organization.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Organization $organization
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForOrganization(Builder $query, Organization $organization): Builder
    {
        return $query->where('organization_id', $organization->id);
    }

    /**
     * Scope a query to only include models for the current authenticated user's organization.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentOrganization(Builder $query): Builder
    {
        if (Auth::check()) {
            return $query->where('organization_id', Auth::user()->organization_id);
        }

        return $query;
    }
}