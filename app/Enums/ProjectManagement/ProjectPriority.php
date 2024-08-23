<?php

namespace App\Enums\ProjectManagement;

enum ProjectPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}