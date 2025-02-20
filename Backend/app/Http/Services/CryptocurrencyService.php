<?php

namespace App\Http\Services;

use App\Models\Cryptocurrency;
use App\Models\PriceHistory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CryptocurrencyService
{
    // ✅ Validate cryptocurrency data
    public function validateCryptoData($data, $id = null)
    {
        $rules = [
            'name' => 'required|string|unique:cryptocurrencies,name,' . ($id ?? 'NULL') . ',id',
            'currentPrice' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }

    // ✅ Add a new cryptocurrency
    public function addCrypto($data)
    {
        $crypto = Cryptocurrency::create([
            'name' => $data['name'],
            'currentPrice' => $data['currentPrice'],
            'logo' => $data['logo'] ?? null,
        ]);

       

        return $crypto;
    }

    // ✅ Update an existing cryptocurrency
    public function updateCrypto($id, $data)
    {
        $crypto = Cryptocurrency::findOrFail($id);

        $crypto->update([
            'name' => $data['name'],
            'currentPrice' => $data['currentPrice'],
            'logo_path' => $data['logo_path'] ?? $crypto->logo_path,
        ]);

        $this->logPriceHistory($crypto->id, $data['currentPrice']);

        return $crypto;
    }

    // ✅ Log price history
    private function logPriceHistory($cryptoId, $currentPrice)
    {
        PriceHistory::create([
            'cryptocurrency_id' => $cryptoId,
            'date' => now()->format('Y-m-d'),
            'value' => $currentPrice,
        ]);
    }

    // ✅ Fetch current prices
    public function getCurrentPrices()
    {
        return Cryptocurrency::all()->map(function ($crypto) {
            return [
                'name' => $crypto->name,
                'currentPrice' => $crypto->currentPrice,
                'date' => now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
            ];
        });
    }
}
