<?php

namespace App\Enums\Billing\Payments;

enum InvoiceStatus: string
{
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case SENT = 'sent';
}