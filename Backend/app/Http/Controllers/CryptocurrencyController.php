<?php

namespace App\Http\Controllers;

use App\Models\Cryptocurrency;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CryptocurrencyController extends Controller
{
    // Route pour récupérer les prix actuels
    public function getCurrentPrices()
    {
        $cryptos = Cryptocurrency::all()->map(function ($crypto) {
            return [
                'id' => $crypto->id,
                'name' => $crypto->name,
                'currentPrice' => $crypto->currentPrice,
                'date' => now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
                'updated_at' => $crypto->updated_at,
            ];
        });

        return response()->json($cryptos);
    }

    // Route pour récupérer l'historique des prix sur 30 jours
    public function getPriceHistory($cryptoName)
    {
        $cryptocurrency = Cryptocurrency::where('name', $cryptoName)->first();

        if (!$cryptocurrency) {
            return response()->json(['error' => 'Cryptocurrency not found'], 404);
        }

        // Récupérer l'historique des prix des 30 derniers jours
        $history = $cryptocurrency->priceHistory()->where('date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        $priceHistory = $history->map(function ($entry) {
            return [
                'date' => $entry->date,
                'value' => $entry->value,
            ];
        });

        return response()->json([
            'name' => $cryptocurrency->name,
            'price_history' => $priceHistory
        ]);
    }
}
