<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoBankStore extends Model
{
    use HasFactory;
    protected $fillable = [
        'idCryptoWallet',
        'quantity',
        'unitPrice',
        'totalPrice',
        'operationStatus',
        'operationDate'
    ];

    public function cryptoWallet()
    {
        return $this->belongsTo(CryptoWallet::class, 'idCryptoWallet');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'idCryptoBankStore');
    }
}
