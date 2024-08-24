<?php

namespace App\Enums\HRM\Employee;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case ON_LEAVE = 'on_leave';

    /**
     * Get all the possible statuses.
     *
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::PRESENT->value,
            self::ABSENT->value,
            self::LATE->value,
            self::ON_LEAVE->value,
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
            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',
            self::LATE => 'Late',
            self::ON_LEAVE => 'On Leave',
        };
    }
}