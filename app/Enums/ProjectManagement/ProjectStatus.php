<?php

namespace App\Enums\ProjectManagement;

enum ProjectStatus: string
{
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
    case STALLED = 'stalled';
    case COMPLETED = 'completed';
    case INPROGRESS = 'in-progress';
}