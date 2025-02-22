<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');  // Vérifie si l'utilisateur est authentifié
    }

    public function createWallet(Request $request)
    {
        $user = Auth::user();  // Récupérer l'utilisateur authentifié

        // Vérifier si l'utilisateur a un wallet
        $wallet = Wallet::where('idUser', $user->id)->first();
        if (!$wallet) {
            // Si aucun wallet n'est trouvé, créer un nouveau wallet
            $wallet = Wallet::create([
                'idUser' => $user->id,
                'balance' => 0, // Solde initial
            ]);
        }


        return response()->json(['wallet' => $wallet], 201);
    }
    public function getWalletInfo()
{
    $user = Auth::user();

    // Récupérer le portefeuille de l'utilisateur
    $wallet = Wallet::where('idUser', $user->id)->first();

    if (!$wallet) {
        return response()->json(['error' => 'Wallet not found.'], 404);
    }

    return response()->json([
        'balance' => $wallet->balance,
        'public_address' => $wallet->public_address,
        'private_address' => $wallet->private_address, // ⚠️ Peut-être à masquer pour la sécurité
    ]);
}

}
