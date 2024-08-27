<?php

namespace App\Models\Organization;

use App\Models\Administration\StorageSpace;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OrganizationStorageSpace extends Model
{
    use BelongsToOrganization, HasUuids;

    protected $fillable = ['organization_id', 'storage_space_id', 'allocated_storage_in_gb', 'used_storage_in_gb'];

    /**
     * Get the organization that owns the OrganizationStorageSpace
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the storageSpace that owns the OrganizationStorageSpace
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storageSpace(): BelongsTo
    {
        return $this->belongsTo(StorageSpace::class);
    }

    /**
     * Define a polymorphic one-to-many relationship with the OrganizationCartItem model.
     *
     * This method allows retrieving all cart items associated with the current model instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function cartItems(): MorphMany
    {
        return $this->morphMany(OrganizationCartItem::class, 'item');
    }


    /**
     * Calculate available storage for the organization.
     */
    public function availableStorage(): float
    {
        return $this->allocated_storage_in_gb - $this->used_storage_in_gb;
    }

    /**
     * Update used storage for the organization.
     */
    public function updateUsedStorage(float $newUsage): void
    {
        $this->used_storage_in_gb += $newUsage;
        $this->save();
    }
}