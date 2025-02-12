<?php
namespace App\Http\Controllers;

use App\Http\Services\CryptocurrencyService;
use App\Models\Cryptocurrency;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    public function getCurrentPrices()
    {
        $cryptos = Cryptocurrency::all()->map(function ($crypto) {
            return [
                'name' => $crypto->name,
                'currentPrice' => $crypto->currentPrice,
                'date' => now()->format('Y-m-d'),
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->name)) . '.png'),
            ];
        });

        return response()->json($cryptos);
    }

    // Get price history for a specific cryptocurrency
    public function getPriceHistory($cryptoName)
    {
        $cryptocurrency = Cryptocurrency::where('name', $cryptoName)->first();

        if (!$cryptocurrency) {
            return response()->json(['error' => 'Cryptocurrency not found'], 404);
        }

        // Retrieve price history for the last 30 days
        $history = $cryptocurrency->priceHistory()->where('date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        $priceHistory = $history->map(function ($entry) {
            return [
                'date' => $entry->date,
                'value' => $entry->value,
            ];
        });

        return response()->json([
            'name' => $cryptocurrency->name,
            'price_history' => $priceHistory
        ]);
    }
}
