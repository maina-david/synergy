<?php

namespace App\Models\Administration;

use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StructureType extends Model
{
    use HasFactory, SoftDeletes, HasUuids, AssociatedToUser;

    protected $fillable = [
        'organization_id',
        'user_id',
        'name',
        'active'
    ];

    /**
     * Get all of the structures for the StructureType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function structures(): HasMany
    {
        return $this->hasMany(Structure::class);
    }

    /**
     * Scope a query to only include active structure types.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where('active', true);
    }
}