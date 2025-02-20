<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{public function __construct()
    {
        $this->middleware('auth:sanctum');  // Vérifie si l'utilisateur est authentifié
    }

    public function createWallet(Request $request)
    {
        $user = Auth::user();  // Récupérer l'utilisateur authentifié

        // Créez un wallet pour cet utilisateur
        $wallet = Wallet::create([
            'idUser' => $user->id,
            'balance' => $request->balance ?? 0,
        ]);

        return response()->json(['wallet' => $wallet], 201);
    }

}
