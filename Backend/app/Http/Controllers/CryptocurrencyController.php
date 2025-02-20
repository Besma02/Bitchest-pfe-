<?php
namespace App\Http\Controllers;

use App\Http\Services\CryptocurrencyService;
use Illuminate\Http\Request;

class CryptocurrencyController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptocurrencyService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    // Store a new cryptocurrency
    public function store(Request $request)
    {
        $validated = $this->cryptoService->validateCryptoData($request->all());
        $crypto = $this->cryptoService->addCrypto($validated);

        return response()->json(['message' => 'Cryptocurrency added successfully', 'data' => $crypto], 201);
    }

    // Update an existing cryptocurrency
    public function update(Request $request, $id)
    {
        $validated = $this->cryptoService->validateCryptoData($request->all(), $id);
        $crypto = $this->cryptoService->updateCrypto($id, $validated);

        return response()->json(['message' => 'Cryptocurrency updated successfully', 'data' => $crypto], 200);
    }

    // Get current prices of all cryptocurrencies
    protected $cryptocurrencyService;

    public function __construct(CryptocurrencyService $cryptocurrencyService)
    {
        $this->cryptocurrencyService = $cryptocurrencyService;
    }

    // Route pour récupérer les prix actuels
    public function getCurrentPrices()
    {
        $cryptosData = $this->cryptocurrencyService->getCurrentPrices();
        return response()->json($cryptosData);
    }

    // Get price history for a specific cryptocurrency
    public function getPriceHistory($cryptoName)
    {
        $priceHistory = $this->cryptocurrencyService->getPriceHistory($cryptoName);

        if (!$priceHistory) {
            return response()->json(['error' => 'Cryptocurrency not found'], 404);
        }

        return response()->json([
            'name' => $cryptoName,
            'price_history' => $priceHistory
        ]);
    }
}

