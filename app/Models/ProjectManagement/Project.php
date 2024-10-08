<?php

namespace App\Models\ProjectManagement;

use App\Enums\ProjectManagement\ProjectPriority;
use App\Enums\ProjectManagement\ProjectStatus;
use App\Models\Administration\Organization;
use App\Models\User;
use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasUuids, BelongsToOrganization, AssociatedToUser;

    protected $fillable = [
        'user_id',
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'budget',
        'priority',
        'status',
        'last_updated_at',
        'active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => ProjectStatus::class,
        'priority' => ProjectPriority::class,
        'last_updated_at' => 'datetime'
    ];

    /**
     * Get the user that created the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the users assigned to the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_participants');
    }

    /**
     * Scope a query to only include active Projects.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include Projects with a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}