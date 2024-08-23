<?php

namespace App\Enums\Billing\Subscription;

enum SubscriptionType: string
{
    case FREE = 'free';
    case TRIAL = 'trial';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case BIANNUAL = 'biannual';
    case ANNUAL = 'annual';
}
