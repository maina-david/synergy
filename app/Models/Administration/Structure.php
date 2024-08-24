<?php

namespace App\Models\Administration;

use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    use HasFactory, SoftDeletes, HasUuids, BelongsToOrganization, AssociatedToUser;

    protected $fillable = [
        'organization_id',
        'user_id',
        'structure_type_id',
        'parent_id',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the organization that owns the Structure.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the structure type that owns the Structure.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function structureType(): BelongsTo
    {
        return $this->belongsTo(StructureType::class);
    }

    /**
     * Get all of the children for the Structure.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Structure::class, 'parent_id');
    }

    /**
     * Get the parent that owns the Structure.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Structure::class, 'parent_id');
    }

    /**
     * Get the Structure's name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value): string => ucfirst($value),
            set: fn(string $value): string => strtolower($value)
        );
    }

    /**
     * Scope a query to only include active structures.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}