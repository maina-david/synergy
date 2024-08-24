<?php

namespace App\Enums\HRM;

enum ApplicantStatus: string
{
    case APPLIED = 'applied';
    case UNDER_REVIEW = 'under_review';
    case INTERVIEW_SCHEDULED = 'interview_scheduled';
    case OFFERED = 'offered';
    case REJECTED = 'rejected';

    /**
     * Get all possible statuses.
     *
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::APPLIED->value,
            self::UNDER_REVIEW->value,
            self::INTERVIEW_SCHEDULED->value,
            self::OFFERED->value,
            self::REJECTED->value,
        ];
    }

    /**
     * Get a human-readable label for the status.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::APPLIED => 'Applied',
            self::UNDER_REVIEW => 'Under Review',
            self::INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::OFFERED => 'Offered',
            self::REJECTED => 'Rejected',
        };
    }
}