<?php

namespace App\Models\HRM;

use App\Enums\HRM\RecruitmentStatus;
use App\Models\Administration\Organization;
use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Recruitment extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToOrganization, AssociatedToUser;

    protected $fillable = [
        'organization_id',
        'user_id',
        'job_title',
        'description',
        'requirements',
        'post_date',
        'application_deadline',
        'status',
        'salary_range',
    ];

    protected $casts = [
        'post_date' => 'date',
        'application_deadline' => 'date',
        'status' => RecruitmentStatus::class,
    ];

    /**
     * Get the organization that the Recruitment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get all of the applicants for the Recruitment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    /**
     * Accessor for formatted post date.
     *
     * @return string
     */
    public function getFormattedPostDateAttribute(): string
    {
        return $this->post_date->format('F j, Y');
    }

    /**
     * Accessor for formatted application deadline.
     *
     * @return string
     */
    public function getFormattedApplicationDeadlineAttribute(): string
    {
        return $this->application_deadline->format('F j, Y');
    }

    /**
     * Check if the application deadline has passed.
     *
     * @return bool
     */
    public function isDeadlinePassed(): bool
    {
        return Carbon::now()->greaterThan($this->application_deadline);
    }
}