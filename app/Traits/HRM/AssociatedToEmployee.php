<?php

namespace App\Traits\HRM;

use Illuminate\Support\Facades\Auth;

trait AssociatedToEmployee
{
    /**
     * Boot the trait and automatically set the employee_id when the model is created.
     *
     * @return void
     */
    protected static function bootAssociatedToUser(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && empty($model->employee_id)) {
                $model->employee_id = Auth::id();
            }
        });
    }
}