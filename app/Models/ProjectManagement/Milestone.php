<?php

namespace App\Models\ProjectManagement;

use App\Enums\ProjectManagement\MilestoneStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'due_date',
        'status',
        'assigned_to',
        'completed_at'   
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'status' => MilestoneStatus::class,
        'completed_at' => 'datetime' // casting for completion date
    ];

    /**
     * Get the project that owns the Milestone
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user responsible for the Milestone
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope a query to only include Milestones with a specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include Milestones due before a certain date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueBefore($query, string $date)
    {
        return $query->where('due_date', '<', $date);
    }
}
