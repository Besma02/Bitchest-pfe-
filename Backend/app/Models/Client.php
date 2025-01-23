<?php

namespace App\Models;

class Client extends User
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->role = 'client';
            $model->balance = 0;
        });
    }

    // Indiquer que le modèle utilise la table `users`
    protected $table = 'users';
}
