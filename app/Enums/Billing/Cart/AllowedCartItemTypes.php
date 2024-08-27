<?php

namespace App\Enums\Billing\Cart;

enum AllowedCartItemTypes: string
{
    case MODULE = 'module';
    case STORAGE = 'storage';
}