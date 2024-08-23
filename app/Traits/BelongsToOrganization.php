<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
            if (Auth::user()) {
                $model->organization_id = Auth::user()->organization_id ?? null;
            }
        });

        if (static::class !== User::class) {
            static::addGlobalScope(new class implements Scope {
                public function apply(Builder $builder, Model $model)
                {
                    if (Auth::user()) {
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
     * Check if the model belongs to an organization.
     *
     * @return bool
     */
    public function belongsToOrganization(): bool
    {
        return !is_null($this->organization);
    }
}