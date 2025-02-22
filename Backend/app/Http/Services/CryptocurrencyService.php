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
    public function     validateCryptoData($data, $id = null)
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
    
        // Handle file upload if a new logo is provided
        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            $filename = time() . '_' . $data['logo']->getClientOriginalName();
            $path = $data['logo']->storeAs('public/cryptos', $filename);
            $data['logo'] = str_replace('public/', '', $path); // Save relative path
        } else {
            $data['logo'] = $crypto->logo; // Keep old logo if not updated
        }
    
        // Update cryptocurrency
        $crypto->update([
            'name' => $data['name'],
            'currentPrice' => $data['currentPrice'],
            'logo' => $data['logo'],
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
                'currentPrice' => $crypto->currentPrice,
                'date' => now()->format('Y-m-d'),
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

        // Récupérer l'historique des prix des 30 derniers jours
        $history = $cryptocurrency->priceHistory()->where('date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        return $history->map(function ($entry) {
            return [
                'date' => $entry->date,
                'value' => $entry->value,
            ];
        });
    }

    // ✅ Get cryptocurrency by ID
    public function getCryptoById($id)
    {
        // Fetch cryptocurrency by ID
        $crypto = Cryptocurrency::find($id);

        if (!$crypto) {
            return null;  // Return null if not found
        }

        // Optionally, you can also include the price history for the crypto
        $priceHistory = $crypto->priceHistory()->orderBy('date', 'desc')->take(30)->get();

        return [
            'crypto' => $crypto,
            'price_history' => $priceHistory,
        ];
    }
}
