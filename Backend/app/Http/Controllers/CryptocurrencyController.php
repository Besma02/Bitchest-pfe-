<?php

namespace App\Http\Controllers;

use App\Http\Services\CryptocurrencyService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        return response()->json($this->cryptoService->getCurrentPrices(), 200);
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
            'name' => $cryptoName,
            'price_history' => $priceHistory
        ]);
    }

    // ✅ Store a new cryptocurrency (Admin Only)
    public function store(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $this->cryptoService->validateCryptoData($request->all());

            // Handle file upload if a logo is provided
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/cryptos', $filename);
                $validatedData['logo'] = str_replace('public/', '', $path); // Save relative path
            }

            // Create cryptocurrency
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
            // Validate request data
            $validatedData = $this->cryptoService->validateCryptoData($request->all(), $id);

            // Handle file upload if a new logo is provided
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/cryptos', $filename);
                $validatedData['logo'] = str_replace('public/', '', $path); // Save relative path
            }

            // Update cryptocurrency
            $crypto = $this->cryptoService->updateCrypto($id, $validatedData);

            return response()->json($crypto, 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
