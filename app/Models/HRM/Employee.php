<?php

namespace App\Models\HRM;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, BelongsToOrganization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'job_title',
        'department_id',
        'hire_date',
        'salary',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'float',
    ];

    /**
     * Get the department that the employee belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the employment status (e.g., active, terminated, etc.).
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return ucfirst($this->attributes['status']);
    }

    /**
     * Get the full name of the employee.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get all leave requests made by the employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Determine if the employee is currently active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Determine if the employee has a salary greater than a specific amount.
     *
     * @param float $amount
     * @return bool
     */
    public function hasSalaryGreaterThan(float $amount): bool
    {
        return $this->salary > $amount;
    }
}