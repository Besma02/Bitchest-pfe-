<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\CryptoPurchaseService;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CryptoPurchaseController extends Controller
{
    protected $cryptoPurchaseService;

    public function __construct(CryptoPurchaseService $cryptoPurchaseService)
    {
        $this->cryptoPurchaseService = $cryptoPurchaseService;
    }

    public function addCrypto(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cryptocurrencies,name',
            'currentPrice' => 'required|numeric|min:0.00000001',
            'inStock' => 'required|numeric|min:0',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Vérification supplémentaire pour éviter les doublons
        $normalizedName = strtolower(trim($request->name));
        $exists = Cryptocurrency::whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])->exists();

        if ($exists) {
            return response()->json(['error' => 'This cryptocurrency already exists (case insensitive).'], 400);
        }

        $result = $this->cryptoPurchaseService->addCrypto($request->all());

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json($result, 201);
    }

    public function editCrypto(Request $request, $id)
    {


        try {
            $crypto = Cryptocurrency::findOrFail($id);

            if ($request->has('name')) {
                $crypto->name = $request->input('name');
            }
            if ($request->has('currentPrice')) {
                $crypto->currentPrice = (float)$request->input('currentPrice');
            }
            if ($request->has('inStock')) {
                $crypto->inStock = (float)$request->input('inStock');
            }

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si ce n'est pas l'image par défaut
                if ($crypto->image !== 'default_crypto.png') {
                    Storage::disk('public')->delete('cryptos/' . $crypto->image);
                }

                // Utiliser le nom de la crypto pour l'image, avec l'extension '.png'
                $imageName = strtolower(str_replace(' ', '_', $crypto->name)) . '.png';

                // Enregistrer l'image avec le nom spécifique
                $path = $request->file('image')->storeAs('cryptos', $imageName, 'public');
                $crypto->image = basename($path); // Mettre à jour le chemin relatif de l'image
            }

            $crypto->save();

            return response()->json([
                'success' => true,
                'data' => $crypto
            ]);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    public function sellCrypto(Request $request)
    {
        $request->validate([
            'crypto_id' => 'required|exists:cryptocurrencies,id',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $result = $this->cryptoPurchaseService->sellCrypto($request->crypto_id, $request->quantity, $request->price);

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
    public function getTotalPurchases()
    {
        try {
            // Get the total crypto purchases from the service
            $totalPurchases = $this->cryptoPurchaseService->getTotalCryptoPurchases();

            // Check if there's an error message returned from the service
            if (isset($totalPurchases['error'])) {
                return response()->json([
                    'error' => $totalPurchases['error'],
                ], 400); // Respond with a 400 status if there's an error
            }

            // Return the total crypto purchase amount
            return response()->json($totalPurchases, 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
