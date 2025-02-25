<?php

namespace App\Http\Services;

use App\Models\Cryptocurrency;
use App\Models\PriceHistory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

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
        return Cryptocurrency::create([
            'name' => $data['name'],
            'currentPrice' => $data['currentPrice'],
            'image' => $data['image'] ?? null,
        ]);
    }

    // ✅ Update an existing cryptocurrency
    public function updateCrypto($id, $data)
    {
        $crypto = Cryptocurrency::findOrFail($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $filename = time() . '_' . $data['image']->getClientOriginalName();
            $path = $data['image']->storeAs('public/cryptos', $filename);
            $data['image'] = str_replace('public/', '', $path); // Save relative path
        } else {
            $data['image'] = $crypto->image; // Keep old logo if not updated
        }

        // Update cryptocurrency
        $crypto->update([
            'name' => $data['name'],
            'currentPrice' => $data['currentPrice'],
            'image' => $data['image'],
        ]);

        // Log price history
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
                'id' => $crypto->id,
                'name' => $crypto->name,
                'currentPrice' => $crypto->priceHistory()->where('date', Carbon::now()->format('Y-m-d'))->value('value'),
                'date' => Carbon::now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
            ];
        });
    }

    // ✅ Get price history for a cryptocurrency
    public function getPriceHistory($cryptoName)
    {
        $cryptocurrency = Cryptocurrency::where('name', $cryptoName)->first();

        if (!$cryptocurrency) {
            return null;
        }

        return $cryptocurrency->priceHistory()->where('date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($entry) {
                return [
                    'date' => $entry->date,
                    'value' => $entry->value,
                ];
            });
    }

    // ✅ Get cryptocurrency by ID
    public function getCryptoById($id)
    {
        $crypto = Cryptocurrency::find($id);

        if (!$crypto) {
            return null;
        }

        $priceHistory = $crypto->priceHistory()->orderBy('date', 'desc')->take(30)->get();

        return [
            'crypto' => $crypto,
            'price_history' => $priceHistory,
        ];
    }
}
