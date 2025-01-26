<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformFinance extends Model
{
    protected $fillable = ['total_income'];

/**
 * Ajouter un montant aux revenus totaux.
 */
public static function addIncome($amount)
{
    $finance = self::first();
    if (!$finance) {
        $finance = self::create(['total_income' => 0]);
    }
    $finance->increment('total_income', $amount);
}

/**
 * Récupérer le total des revenus.
 */
public static function getIncome()
{
    return self::first()->total_income ?? 0;
}
}
