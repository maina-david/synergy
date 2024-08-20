<?php

namespace App\Enums\Admin;

enum Role: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ORG_ADMIN = 'Organization Admin';
}