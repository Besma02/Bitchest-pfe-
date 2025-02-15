<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'idCrypto',
        'idWallet',
        'quantity',
        'status'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'idWallet');
    }

    public function cryptoBankStores()
    {
        return $this->hasMany(CryptoBankStore::class, 'idCryptoWallet');
    }
}
