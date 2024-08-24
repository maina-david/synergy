<?php

namespace App\Models\HRM;

use App\Enums\HRM\LeaveRequestStatus;
use App\Traits\HRM\AssociatedToEmployee;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes, AssociatedToEmployee;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'request_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_date' => 'date',
        'status' => LeaveRequestStatus::class
    ];

    /**
     * Get the employee that made the leave request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the leave type associated with the leave request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get all of the comments for the LeaveRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(LeaveRequestComment::class);
    }

    /**
     * Check if the leave request is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === LeaveRequestStatus::APPROVED;
    }
}