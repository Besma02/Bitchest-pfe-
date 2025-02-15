<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'idCryptoBankStore',
        'idRecipient',
        'fee',
        'date'
    ];

    public function cryptoBankStore()
    {
        return $this->belongsTo(CryptoBankStore::class, 'idCryptoBankStore');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'idRecipient');
    }
}
