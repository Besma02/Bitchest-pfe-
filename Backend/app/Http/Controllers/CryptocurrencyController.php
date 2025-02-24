<?php

namespace App\Http\Controllers;

use App\Http\Services\CryptocurrencyService;
use App\Models\Cryptocurrency;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CryptocurrencyController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptocurrencyService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    // ✅ Get all cryptocurrencies with current prices
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

        $cryptosData = $cryptos->map(function ($crypto) {
            return [
                'id' => $crypto->id,
                'name' => $crypto->name,
                'currentPrice' => $crypto->priceHistory()->latest('date')->value('value'),
                'date' => Carbon::now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
                'updated_at' => $crypto->updated_at,
            ];
        });

        return response()->json($cryptosData);
    }

    private function generateTodayPrice($crypto)
    {
        $lastPrice = $crypto->priceHistory()->latest('date')->value('value') ?? $crypto->currentPrice;
        $variation = ((rand(0, 99) > 40) ? 1 : -1) * (rand(1, 10) * 0.01);
        $newPrice = max(0, $lastPrice + $variation);

        $crypto->priceHistory()->create([
            'date' => Carbon::now()->format('Y-m-d'),
            'value' => $newPrice,
        ]);
    }

    // ✅ Get price history for a specific cryptocurrency
    public function getPriceHistory($cryptoName)
    {
        $priceHistory = $this->cryptoService->getPriceHistory($cryptoName);

        if (empty($priceHistory)) {
            return response()->json(['error' => 'Cryptocurrency not found'], 404);
        }

        return response()->json([
            'name' => $cryptoName,
            'price_history' => $priceHistory
        ]);
    }

    // ✅ Store a new cryptocurrency (Admin Only)
    public function store(Request $request)
    {
        try {
            $validatedData = $this->cryptoService->validateCryptoData($request->all());

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/cryptos', $filename);
                $validatedData['logo'] = str_replace('public/', '', $path);
            }

            $crypto = $this->cryptoService->addCrypto($validatedData);

            return response()->json($crypto, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ Update cryptocurrency (Admin Only)
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $this->cryptoService->validateCryptoData($request->all(), $id);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/cryptos', $filename);
                $validatedData['logo'] = str_replace('public/', '', $path);
            }

            $crypto = $this->cryptoService->updateCrypto($id, $validatedData);

            return response()->json($crypto, 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ Show cryptocurrency by ID (Admin/Client)
    public function show($id)
    {
        try {
            $cryptoData = $this->cryptoService->getCryptoById($id);

            if (!$cryptoData) {
                return response()->json(['error' => 'Cryptocurrency not found'], 404);
            }

            return response()->json($cryptoData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
