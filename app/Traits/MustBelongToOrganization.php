<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;

trait MustBelongToOrganization
{
    /**
     * Check if the user belongs to an organization.
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