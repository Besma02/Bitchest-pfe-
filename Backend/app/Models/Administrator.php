<?php

namespace App\Models;

class Administrator extends User
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->role = 'administrator';
        });
    }
}
