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
        'quantity'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class,  'idWallet');
    }
    public function cryptoCurrency()
    {
        return $this->belongsTo(Cryptocurrency::class,  'idCrypto');
    }
    public function transactions()
{
    return $this->hasMany(Transaction::class, 'idCryptoWallet');
}

}