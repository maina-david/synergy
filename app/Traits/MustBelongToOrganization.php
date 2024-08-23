<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

trait MustBelongToOrganization
{
    /**
     * Boot the trait to add a creating model event listener and global scope.
     *
     * @return void
     */
    public static function bootMustBelongToOrganization()
    {
        static::creating(function ($model) {
            if (Auth::user()) {
                if (isset($model->organization_id)) {
                    $model->organization_id = Auth::user()->organization_id ?? null;
                }
            }
        });

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
     * Redirect the user to the organization setup page if they don't belong to an organization.
     *
     * @return RedirectResponse|null
     */
    public function ensureBelongsToOrganization()
    {
        if (!$this->belongsToOrganization()) {
            return redirect()->route('organization.setup');
        }

        return null;
    }
}