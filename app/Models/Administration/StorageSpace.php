<?php

namespace App\Models\Administration;

use App\Enums\Admin\StorageType;
use App\Models\Organization\OrganizationStorageSpace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageSpace extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'total_storage_in_gb',
        'allocated_storage_in_gb',
        'price_per_gb',
        'active'
    ];

    protected $casts = [
        'type' => StorageType::class,
        'total_storage_in_gb' => 'float',
        'allocated_storage_in_gb' => 'float',
        'price_per_gb' => 'float',
        'active' => 'boolean'
    ];

    /**
     * Get all of the organizationStorages for the StorageSpace
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizationStorageSpaces(): HasMany
    {
        return $this->hasMany(OrganizationStorageSpace::class);
    }

    /**
     * Calculate remaining storage capacity in the system.
     */
    public function availableStorage(): float
    {
        return $this->total_storage_in_gb - $this->allocated_storage_in_gb;
    }

    /**
     * Update allocated storage when an organization is assigned storage.
     */
    public function updateAllocatedStorage(float $allocatedStorage): void
    {
        $this->allocated_storage_in_gb += $allocatedStorage;
        $this->save();
    }

    /**
     * Calculate the cost for a given amount of storage.
     */
    public function calculateCost(float $storageInGb): float
    {
        return $this->price_per_gb * $storageInGb;
    }
}