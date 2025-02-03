<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->role = 'administrator';
        });
    }
}
