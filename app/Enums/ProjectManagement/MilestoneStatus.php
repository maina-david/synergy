<?php

namespace App\Enums\ProjectManagement;

enum MilestoneStatus: string
{
    case STALLED = 'stalled';
    case MET = 'met';
}