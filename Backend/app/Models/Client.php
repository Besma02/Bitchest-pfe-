<?php

namespace App\Models;

class Client extends User
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->type = 'client';
            $model->balance = 500;
        });
    }

    // Indiquer que le modèle utilise la table `users`
    protected $table = 'users';
}
