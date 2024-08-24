<?php

namespace App\Traits\Users;

use Illuminate\Support\Facades\Auth;

trait AssociatedToUser
{
    /**
     * Boot the trait and automatically set the user_id when the model is created.
     *
     * @return void
     */
    protected static function bootAssociatedToUser(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }
}