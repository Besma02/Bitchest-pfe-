<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * Valeur totale des cryptos sur la plateforme
     */
    public function getPlatformTotalValue()
    {
        return DB::table('cryptocurrencies as c')
            ->leftJoin('crypto_wallets as cw', 'cw.idCrypto', '=', 'c.id')
            ->select(
                DB::raw('SUM(c.inStock * c.currentPrice) + COALESCE(SUM(cw.quantity * c.currentPrice), 0) AS totalValue')
            )
            ->first();
    }

    /**
     * Valeur par crypto sur la plateforme
     */
    public function getPlatformCryptoDetails()
    {
        return DB::table('cryptocurrencies as c')
            ->leftJoin('crypto_wallets as cw', 'cw.idCrypto', '=', 'c.id')
            ->select(
                'c.name',
                'c.currentPrice',
                DB::raw('c.inStock + COALESCE(SUM(cw.quantity), 0) as totalQuantity'),
                DB::raw('(c.inStock + COALESCE(SUM(cw.quantity), 0)) * c.currentPrice as totalValue')
            )
            ->groupBy('c.id', 'c.name', 'c.currentPrice')
            ->get();
    }

    /**
     * Valeur totale du portefeuille de l'utilisateur
     */
    public function getUserPortfolio($userId)
    {
        return DB::table('wallets as w')
            ->join('crypto_wallets as cw', 'cw.idWallet', '=', 'w.id')
            ->join('cryptocurrencies as c', 'cw.idCrypto', '=', 'c.id')
            ->where('w.idUser', $userId)
            ->select(
                DB::raw('SUM(cw.quantity * c.currentPrice) as totalValue')
            )
            ->first();
    }

    /**
     * Détails par crypto pour l'utilisateur
     */
    public function getUserCryptoDetails($userId)
    {
        return DB::table('wallets as w')
            ->join('crypto_wallets as cw', 'cw.idWallet', '=', 'w.id')
            ->join('cryptocurrencies as c', 'cw.idCrypto', '=', 'c.id')
            ->where('w.idUser', $userId)
            ->select(
                'c.name',
                'c.currentPrice',
                'cw.quantity',
                DB::raw('(cw.quantity * c.currentPrice) as value')
            )
            ->get();
    }

    /**
     * Top 5 des cryptos les plus échangées (en volume)
     */
    public function getTopCryptos()
    {
        return DB::table('transactions as t')
            ->join('cryptocurrencies as c', 't.idCryptoWallet', '=', 'c.id')
            ->select(
                'c.name',
                DB::raw('SUM(t.quantity) as totalVolume')
            )
            ->groupBy('c.name')
            ->orderByDesc('totalVolume')
            ->get();
    }
}
