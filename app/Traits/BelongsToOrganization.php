<?php

namespace App\Traits;

use App\Models\Organization\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Scope;
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
        static::creating(function ($model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });

        static::updating(function ($model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });

        static::saving(function ($model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });

        if (static::class !== User::class) {
            static::addGlobalScope(new class implements Scope {
                public function apply(Builder $builder, Model $model): void
                {
                    if (Auth::check()) {
                        $organizationId = Auth::user()->organization_id;
                        if ($organizationId) {
                            $builder->where('organization_id', $organizationId);
                        }
                    }
                }
            });
        }
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

    /**
     * Temporarily disable the global organization scope for special queries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutGlobalScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope(new class implements Scope {
            public function apply(Builder $builder, Model $model): void
            {
                if (Auth::check()) {
                    $organizationId = Auth::user()->organization_id;
                    if ($organizationId) {
                        $builder->where('organization_id', $organizationId);
                    }
                }
            }
        });
    }
}
