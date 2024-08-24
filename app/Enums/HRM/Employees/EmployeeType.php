<?php

namespace App\Enums\HRM\Employees;

enum EmployeeType: string
{
    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case CONTRACT = 'contract';
    case INTERN = 'intern';
    case CONSULTANT = 'consultant';
    case TEMPORARY = 'temporary';
}