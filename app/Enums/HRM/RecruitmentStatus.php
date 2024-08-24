<?php

namespace App\Enums\HRM;

enum RecruitmentStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case ON_HOLD = 'on_hold';
    case CANCELLED = 'cancelled';

    /**
     * Get all possible statuses.
     *
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::OPEN->value,
            self::CLOSED->value,
            self::ON_HOLD->value,
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
            self::OPEN => 'Open',
            self::CLOSED => 'Closed',
            self::ON_HOLD => 'On Hold',
            self::CANCELLED => 'Cancelled',
        };
    }
}