<?php

namespace App\Models\ProjectManagement;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectComment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'project_id',
        'user_id',
        'parent_id',
        'comment',
        'is_resolved'  
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_resolved' => 'boolean',  // casting the new field as boolean
    ];

    /**
     * Get the user that owns the ProjectComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the ProjectComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all of the children for the Project Comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(ProjectComment::class, 'parent_id');
    }

    /**
     * Get the parent that owns the Project Comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectComment::class, 'parent_id');
    }

    /**
     * Scope a query to only include resolved comments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope a query to only include unresolved comments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope a query to only include parent comments (top-level comments).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParentsOnly($query)
    {
        return $query->whereNull('parent_id');
    }
}
