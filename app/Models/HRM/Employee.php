<?php

namespace App\Models\HRM;

use App\Traits\MustBelongToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, MustBelongToOrganization;
}