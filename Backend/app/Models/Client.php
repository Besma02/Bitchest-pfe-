<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->role = 'client';
            $model->balance;
        });
    }

    // Indiquer que le modèle utilise la table `users`
    protected $table = 'users';
}
