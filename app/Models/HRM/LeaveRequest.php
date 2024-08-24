<?php

namespace App\Models\HRM;

use App\Enums\HRM\LeaveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'leave_id',
        'request_date',
        'status',
        'comments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_date' => 'date',
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
     * Get the leave associated with the leave request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leave(): BelongsTo
    {
        return $this->belongsTo(Leave::class);
    }

    /**
     * Check if the leave request is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === LeaveStatus::APPROVED;
    }
}