<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\CryptoPurchaseService;
use Illuminate\Support\Facades\Auth;

class CryptoPurchaseController extends Controller
{
    protected $cryptoPurchaseService;

    public function __construct(CryptoPurchaseService $cryptoPurchaseService)
    {
        $this->cryptoPurchaseService = $cryptoPurchaseService;
    }

    public function buyCrypto(Request $request)
    {
        $request->validate([
            'crypto_id' => 'required|exists:cryptocurrencies,id',
            'quantity' => 'required|numeric|min:0.0001',
            'price' => 'required|numeric|min:0.01',
        ]);

        $result = $this->cryptoPurchaseService->buyCrypto($request->crypto_id, $request->quantity, $request->price);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json(['message' => $result['message']], 200);
    }

    public function getUserWallet()
    {
        $result = $this->cryptoPurchaseService->getUserWallet();

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 404);
        }

        return response()->json($result);
    }

    public function getUserTransactions()
    {
        $user = Auth::user();
        $result = $this->cryptoPurchaseService->getUserTransactions($user->role);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 404);
        }

        return response()->json($result);
    }
}
