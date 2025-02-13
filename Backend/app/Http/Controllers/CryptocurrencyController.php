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
        $cryptos = Cryptocurrency::all();

        foreach ($cryptos as $crypto) {
            // Vérifie si les prix pour aujourd'hui existent
            $today = Carbon::now()->format('Y-m-d');
            $existingPrice = $crypto->priceHistory()->where('date', $today)->first();

            // Si le prix d'aujourd'hui n'existe pas, génère-le
            if (!$existingPrice) {
                $this->generateTodayPrice($crypto);
            }
        }

        $cryptosData = $cryptos->map(function ($crypto) {
            return [
                'name' => $crypto->name,
                'currentPrice' => $crypto->priceHistory()->latest('date')->value('value'),
                'date' => Carbon::now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
            ];
        });

        return response()->json($cryptosData);
    }

    // Méthode pour générer le prix d'aujourd'hui
    private function generateTodayPrice($crypto)
    {
        // Prendre le dernier prix ou un prix aléatoire initial
        $lastPrice = $crypto->priceHistory()->latest('date')->value('value') ?? $crypto->currentPrice;

        // Générer une variation aléatoire
        $variation = ((rand(0, 99) > 40) ? 1 : -1) * (rand(1, 10) * 0.01);
        $newPrice = max(0, $lastPrice + $variation);

        // Crée l'entrée pour le prix d'aujourd'hui
        $crypto->priceHistory()->create([
            'date' => Carbon::now()->format('Y-m-d'),
            'value' => $newPrice,
        ]);
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