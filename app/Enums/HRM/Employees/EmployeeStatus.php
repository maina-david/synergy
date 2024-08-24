<?php

namespace App\Enums\HRM\Employees;

enum EmployeeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PROBATION = 'probation';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';
    case RESIGNED = 'resigned';
    case RETIRED = 'retired';
    case ON_LEAVE = 'on_leave';
}