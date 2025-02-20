<?php
namespace App\Http\Services;

use App\Models\Cryptocurrency;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CryptocurrencyService
{
    public function addCrypto(array $data)
    {
        return Cryptocurrency::create($data);
    }

    public function updateCrypto($id, array $data)
    {
        $crypto = Cryptocurrency::findOrFail($id);
        $crypto->update($data);
        return $crypto;
    }

    public function validateCryptoData(array $data, $id = null)
    {
        $rules = [
            'name' => 'required|string|unique:cryptocurrencies,name' . ($id ? ',' . $id : ''),
            'logo' => 'required|string',
            'current_price' => 'required|numeric|min:0',
        ];

        return validator($data, $rules)->validate();
    }

    public function generateTodayPrice($crypto)
    {
        $lastPrice = $crypto->priceHistory()->latest('date')->value('value') ?? $crypto->currentPrice;
        $variation = ((rand(0, 99) > 40) ? 1 : -1) * (rand(1, 10) * 0.01);
        $newPrice = max(0, $lastPrice + $variation);

        $crypto->priceHistory()->create([
            'date' => Carbon::now()->format('Y-m-d'),
            'value' => $newPrice,
        ]);
    }

    public function getCurrentPrices()
    {
        $cryptos = Cryptocurrency::all();

        foreach ($cryptos as $crypto) {
            $today = Carbon::now()->format('Y-m-d');
            $existingPrice = $crypto->priceHistory()->where('date', $today)->first();

            if (!$existingPrice) {
                $this->generateTodayPrice($crypto);
            }
        }

        return $cryptos->map(function ($crypto) {
            return [
                'id' => $crypto->id,
                'name' => $crypto->name,
                'currentPrice' => $crypto->priceHistory()->latest('date')->value('value'),
                'date' => Carbon::now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
            ];
        });
    }

    public function getPriceHistory($cryptoName)
    {
        $cryptocurrency = Cryptocurrency::where('name', $cryptoName)->first();

        if (!$cryptocurrency) {
            return null;
        }

        $history = $cryptocurrency->priceHistory()
            ->where('date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        return $history->map(function ($entry) {
            return [
                'date' => $entry->date,
                'value' => $entry->value,
            ];
        });
    }
}
