<?php

namespace App\Models\HRM;

use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFile extends Model
{
    use HasFactory, AssociatedToUser;
}