<?php

namespace App\Enums\Admin\Organization;

enum OrganizationType: string
{
    case OWNER = 'owner';
    case CLIENT = 'client';
}