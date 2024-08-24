<?php

namespace App\Models\HRM;

use App\Enums\HRM\LeaveStatus;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory, AssociatedToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the employee that owns the leave.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the leave request associated with this leave.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leaveRequest()
    {
        return $this->hasOne(LeaveRequest::class);
    }

    /**
     * Check if the leave is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === LeaveStatus::APPROVED;
    }
}