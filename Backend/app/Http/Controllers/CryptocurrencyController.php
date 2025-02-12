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

    public function store(Request $request)
    {
        $validated = $this->cryptoService->validateCryptoData($request->all());
        $crypto = $this->cryptoService->addCrypto($validated);

        return response()->json(['message' => 'Cryptocurrency added successfully', 'data' => $crypto], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->cryptoService->validateCryptoData($request->all(), $id);
        $crypto = $this->cryptoService->updateCrypto($id, $validated);

        return response()->json(['message' => 'Cryptocurrency updated successfully', 'data' => $crypto], 200);
    }
}
