<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'idUser',
        'publicAdress',
        'privateAdress',
        'balance',  // Ajoutez balance à $fillable
    ];

    protected $casts = [
        'publicAdress' => 'string',
        'privateAdress' => 'string',
        'balance' => 'decimal:2',  // Si vous voulez vous assurer que balance est traité comme un nombre décimal
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function cryptoWallets()
    {
        return $this->hasMany(CryptoWallet::class, 'idWallet');
    }
}

