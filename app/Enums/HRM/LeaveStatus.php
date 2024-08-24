<?php

namespace App\Enums\HRM;

enum LeaveStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    /**
     * Get all the possible statuses.
     *
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::PENDING->value,
            self::APPROVED->value,
            self::REJECTED->value,
            self::CANCELLED->value,
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
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
        };
    }
}