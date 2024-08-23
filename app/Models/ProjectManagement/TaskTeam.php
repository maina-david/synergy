<?php

namespace App\Models\ProjectManagement;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTeam extends Model
{
    protected $fillable = [
        'task_id',
        'user_id'
    ];

    /**
     * Get the task that owns the TaskTeam.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user that is part of the TaskTeam.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}