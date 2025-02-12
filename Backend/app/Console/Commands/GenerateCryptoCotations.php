<?php

namespace App\Console\Commands;

use App\Models\Cryptocurrency;
use App\Models\PriceHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateCryptoCotations extends Command
{
    protected $signature = 'crypto:generate-cotations';
    protected $description = 'Génère les cotations actuelles pour les cryptos et leur historique sur 30 jours';

    private $cryptos = [
        'Bitcoin' => 'cryptos/bitcoin.png',
        'Ethereum' => 'cryptos/ethereum.png',
        'Ripple' => 'cryptos/ripple.png',
        'Cardano' => 'cryptos/cardano.png',
        'Litecoin' => 'cryptos/litecoin.png',
        'NEM' => 'cryptos/nem.png',
        'Bitcoin Cash' => 'cryptos/bitcoin_cash.png',
        'Stellar' => 'cryptos/stellar.png',
        'IOTA' => 'cryptos/iota.png',
        'Dash' => 'cryptos/dash.png',
    ];

    public function handle()
    {
        foreach ($this->cryptos as $crypto => $image) {
            $this->generateHistoricalData($crypto, $image);
        }

        $this->info('Cotations historiques générées avec succès !');
    }

    private function generateHistoricalData($crypto, $image)
    {
        $cryptocurrency = Cryptocurrency::firstOrCreate(
            ['name' => $crypto],
            ['image' => Storage::url($image), 'currentPrice' => rand(50, 100)]
        );

        // S'assurer qu'il y a des historiques pour les 30 derniers jours
        for ($i = 30; $i > 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $existingHistory = $cryptocurrency->priceHistory()->where('date', $date)->first();

            // Si l'historique n'existe pas pour cette date, on le génère
            if (!$existingHistory) {
                // Prendre le dernier prix ou un prix aléatoire initial
                $lastValue = $cryptocurrency->priceHistory()->latest('date')->value('value') ?? $cryptocurrency->currentPrice;

                // Générer une variation aléatoire
                $variation = ((rand(0, 99) > 40) ? 1 : -1) * (rand(1, 10) * 0.01);
                $newValue = max(0, $lastValue + $variation);

                // Enregistrer cet historique
                $cryptocurrency->priceHistory()->create([
                    'date' => $date,
                    'value' => $newValue,
                ]);
            }
        }
    }
}
