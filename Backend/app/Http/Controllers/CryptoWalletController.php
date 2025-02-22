<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\CryptoWallet;
use Illuminate\Support\Facades\Auth;

class CryptoWalletController extends Controller
{
    public function getCryptoPurchases($cryptoId)
    {
        $userId = Auth::id(); // Récupérer l'utilisateur connecté

        // Vérifier si l'utilisateur possède un portefeuille contenant cette crypto
        $cryptoWallet = CryptoWallet::whereHas('wallet', function ($query) use ($userId) {
            $query->where('idUser', $userId);
        })->where('idCrypto', $cryptoId)->first();

        if (!$cryptoWallet) {
            return response()->json(['message' => 'No wallet found for this cryptocurrency.'], 404);
        }

        // Récupérer les transactions liées à ce portefeuille
        $purchases = Transaction::where('idCryptoWallet', $cryptoWallet->id)
            ->where('type', 'buy') // Filtrer uniquement les achats
            ->orderBy('date', 'desc') // Trier du plus récent au plus ancien
            ->get();

        if ($purchases->isEmpty()) {
            return response()->json(['message' => 'No purchases found for this cryptocurrency.'], 404);
        }

        return response()->json($purchases);
    }
}
