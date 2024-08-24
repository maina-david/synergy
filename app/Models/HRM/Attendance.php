<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'clock_in_time',
        'clock_out_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    /**
     * Get the employee that owns the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Calculate the hours worked based on clock-in and clock-out times.
     *
     * @return float|null
     */
    public function calculateHoursWorked(): ?float
    {
        if ($this->clock_in_time && $this->clock_out_time) {
            $hoursWorked = Carbon::parse($this->clock_in_time)->diffInHours(Carbon::parse($this->clock_out_time));
            return $hoursWorked;
        }
        return null;
    }
}