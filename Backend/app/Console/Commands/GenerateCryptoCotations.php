<?php

namespace App\Console\Commands;

use App\Models\CryptoCotation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateCryptoCotations extends Command
{
    protected $signature = 'crypto:generate-cotations';
    protected $description = 'Génère les cotations actuelles pour les cryptos';

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
            $this->generateCotation($crypto, $image);
        }

        $this->info('Cotations générées avec succès !');
    }

    private function generateCotation($crypto, $image)
    {
        $latestCotation = CryptoCotation::where('crypto_name', $crypto)->latest('date')->first();
        $lastValue = $latestCotation ? $latestCotation->value : rand(50, 100);

        $variation = ((rand(0, 99) > 40) ? 1 : -1) * (rand(1, 10) * 0.01);
        $newValue = max(0, $lastValue + $variation);
   
        $imageUrl = Storage::url($image); // Génère une URL publique de l'image
        CryptoCotation::create([
            'crypto_name' => $crypto,
            'image' => $imageUrl,
            'date' => Carbon::now()->format('Y-m-d'),
            'value' => $newValue,
        ]);
    }
}
