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
     * Boot the trait to add a creating model event listener and global scope.
     *
     * @return void
     */
    public static function bootBelongsToOrganization()
    {
        static::creating(function ($model) {
            if (Auth::check() && empty($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });

        if (static::class !== User::class) {
            static::addGlobalScope(new class implements Scope {
                public function apply(Builder $builder, Model $model)
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
     * Get the organization that owns the model
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
        return !is_null($this->organization);
    }

    /**
     * Scope a query to only include Models for Organization.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Organization $organization
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForOrganization(Builder $query, Organization $organization): Builder
    {
        return $query->whereRelation('organization', 'id', $organization->id);
    }
}