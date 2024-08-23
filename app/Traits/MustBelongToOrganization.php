<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

trait MustBelongToOrganization
{
    /**
     * Boot the trait to add a creating model event listener.
     *
     * @return void
     */
    public static function bootLinksToOrganization()
    {
        static::creating(function ($model) {
            if (isset($model->organization_id)) {
                $model->organization_id = Auth::user()->organization_id ?? null;
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
