<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'idWallet', 
        'idCrypto', 
        'quantity', 
        'unit_price', 
        'purchase_date',  
        'status'
    ];

    // Relation avec le Wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'idWallet');
    }

    // Relation avec la table Crypto (CryptoCurrency)
    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'idCrypto'); // Changement ici
    }
    // Relation avec CryptoBankStore
    public function cryptoBankStores()
    {
        return $this->hasMany(CryptoBankStore::class, 'idCryptoWallet');
    }
}
