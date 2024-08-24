<?php

namespace App\Models\HRM;

use App\Models\Administration\Organization;
use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recruitment extends Model
{
    use HasFactory, BelongsToOrganization, AssociatedToUser;

    protected $fillable = [
        'organization_id',
        'user_id',
        'job_title',
        'description',
        'requirements',
        'post_date',
        'application_deadline',
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
}