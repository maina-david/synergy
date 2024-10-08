<?php

namespace App\Enums\HRM;

enum LeaveStatus: string
{
    case UPCOMING = 'upcoming';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Get all the possible statuses.
     *
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::UPCOMING->value,
            self::ONGOING->value,
            self::COMPLETED->value,
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
            self::UPCOMING => 'Upcoming',
            self::ONGOING => 'Ongoing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}