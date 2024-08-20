<?php

namespace App\Enums\Admin\Organization;

enum OrganizationType: string
{
    case OWNER = 'Owner';
    case CLIENT = 'Client';
}