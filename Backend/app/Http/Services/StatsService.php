<?php

namespace App\Http\Services;

use App\Models\Cryptocurrency;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class StatsService
{
    public function getAdminStats()
    {
        $totalValue = Cryptocurrency::sum(DB::raw('currentPrice * (SELECT SUM(quantity) FROM crypto_wallets WHERE crypto_wallets.idCrypto = cryptocurrencies.id)'));

        $totalByCrypto = Cryptocurrency::withSum('cryptoWallets as totalQuantity', 'quantity')
            ->get(['id', 'name', 'currentPrice'])
            ->map(function ($crypto) {
                return [
                    'crypto' => $crypto->name,
                    'quantity' => $crypto->totalQuantity ?? 0,
                    'totalValue' => $crypto->totalQuantity * $crypto->currentPrice
                ];
            });

        $totalPurchases = Transaction::sum('totalPrice');

        return [
            'totalValue' => $totalValue,
            'totalByCrypto' => $totalByCrypto,
            'totalPurchases' => $totalPurchases
        ];
    }

    public function getUserStats($userId, $balance)
    {
        $investedAmount = Transaction::whereHas('cryptoWallet.wallet', function ($query) use ($userId) {
            $query->where('idUser', $userId);
        })->sum('totalPrice');

        $currentValueByCrypto = Cryptocurrency::with(['cryptoWallets' => function ($query) use ($userId) {
            $query->whereHas('wallet', function ($subQuery) use ($userId) {
                $subQuery->where('idUser', $userId);
            });
        }])->get()
            ->map(function ($crypto) {
                $quantity = $crypto->cryptoWallets->sum('quantity');
                return [
                    'crypto' => $crypto->name,
                    'quantity' => $quantity,
                    'currentValue' => $quantity * $crypto->currentPrice
                ];
            });

        return [
            'balance' => $balance,
            'investedAmount' => $investedAmount,
            'currentValueByCrypto' => $currentValueByCrypto
        ];
    }
}
