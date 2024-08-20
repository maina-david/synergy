<?php

namespace App\Enums\Admin;

enum Role: string
{
    case OWNER = 'Owner';
    case ORG_ADMIN = 'Organization Admin';
}