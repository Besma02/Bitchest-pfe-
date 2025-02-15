<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cryptocurrency;
use App\Models\PriceHistory;
use Carbon\Carbon;

class CryptocurrencySeeder extends Seeder
{
    public function run()
    {
        $cryptos = [
            ['name' => 'Bitcoin', 'logo' => 'btc.png'],
            ['name' => 'Ethereum', 'logo' => 'eth.png'],
            ['name' => 'Ripple', 'logo' => 'xrp.png'],
            ['name' => 'Bitcoin Cash', 'logo' => 'bch.png'],
            ['name' => 'Cardano', 'logo' => 'ada.png'],
            ['name' => 'Litecoin', 'logo' => 'ltc.png'],
            ['name' => 'NEM', 'logo' => 'xem.png'],
            ['name' => 'Stellar', 'logo' => 'xlm.png'],
            ['name' => 'IOTA', 'logo' => 'miota.png'],
            ['name' => 'Dash', 'logo' => 'dash.png'],
        ];

        foreach ($cryptos as $cryptoData) {
            // Générer un prix initial réaliste
            $initialPrice =  self::getFirstCotation($cryptoData['name']) * rand(100, 500) + rand(1, 100);

            $crypto = Cryptocurrency::create([
                'name' => $cryptoData['name'],
                'logo' => $cryptoData['logo'],
                'currentPrice' => $initialPrice,
            ]);

            // Générer l'historique des prix sur 30 jours
            $this->generatePriceHistory($crypto);
        }
    }

    // Génère un prix initial basé sur le nom de la crypto
    private function getFirstCotation($cryptoname)
    {
        return ord(substr($cryptoname, 0, 1)) + rand(0, 10);
    }

    // Génère un historique des prix sur 30 jours
    private function generatePriceHistory($crypto)
    {
        $currentPrice = $crypto->currentPrice;
        $date = Carbon::now()->subDays(30);

        for ($i = 0; $i < 30; $i++) {
            $variation =  self::getCotationFor($crypto->name);
            $currentPrice = max(0.01, $currentPrice + $variation); // Évite les prix négatifs

            PriceHistory::create([
                'cryptocurrency_id' => $crypto->id,
                'value' => $currentPrice,
                'date' => $date->copy(),
            ]);

            $date->addDay();
        }
    }

    // Génère une variation de prix aléatoire
    private function getCotationFor($cryptoname)
    {
        return ((rand(0, 99) > 40 ? 1 : -1) * (rand(0, 99) > 49 ? ord(substr($cryptoname, 0, 1)) : ord(substr($cryptoname, -1))) * (rand(1, 10) * 0.01));
    }
}
