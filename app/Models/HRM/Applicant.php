<?php

namespace App\Models\HRM;

use App\Models\Administration\Organization;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Applicant extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'recruitment_id',
        'name',
        'email',
        'phone',
        'resume',
        'status',
        'application_date',
    ];

    /**
     * Get the organization that owns the Applicant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the recruitment that owns the Applicant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }
}