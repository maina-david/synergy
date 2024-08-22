<?php

namespace App\Enums\Admin\Organization;

enum OrganizationCategory: string
{
    case GOVERNMENT = 'government';
    case CORPORATE = 'corporate';
    case NONPROFIT = 'non-profit';
}