<?php

namespace App\Models\HRM;

use App\Enums\HRM\Employees\EmployeeStatus;
use App\Enums\HRM\Employees\EmployeeType;
use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToOrganization, AssociatedToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'department_id',
        'user_id',
        'reports_to',
        'honorific',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'alternative_phone',
        'address',
        'job_title',
        'hire_date',
        'salary',
        'type',
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
        'type' => EmployeeType::class,
        'status' => EmployeeStatus::class
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
     * Get the Employee's supervisor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }

    /**
     * Get all of the subordinates for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'reports_to');
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
     * Get all leaves made by the employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Determine if the employee is currently active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === EmployeeStatus::ACTIVE;
    }
}
