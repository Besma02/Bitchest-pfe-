<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\CryptoWallet;
use App\Models\CryptoBankStore;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CryptoPurchaseController extends Controller
{
    public function buyCrypto(Request $request)
    {
        $request->validate([
            'crypto_id' => 'required|exists:cryptocurrencies,id',
            'quantity' => 'required|numeric|min:0.0001',
            'price' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $cryptoId = $request->crypto_id;
        $quantity = $request->quantity;
        $price = $request->price;
        $totalPrice = $quantity * $price;

        DB::beginTransaction();
        try {
            // Vérifier si l'utilisateur a un wallet
            $wallet = Wallet::where('idUser', $user->id)->first();
            if (!$wallet) {
                return response()->json(['error' => 'Wallet not found.'], 404);
            }

            // Vérifier si le solde est suffisant
            if ($wallet->balance < $totalPrice) {
                return response()->json(['error' => 'Insufficient balance.'], 400);
            }

            
           // Mettre à jour ou créer l'entrée CryptoWallet
$cryptoWallet = CryptoWallet::where('idWallet', $wallet->id)
    ->where('idCrypto', $cryptoId)
    ->first();

if ($cryptoWallet) {
    $cryptoWallet->quantity += $quantity;
    $cryptoWallet->save();
} else {
    // Ajout des valeurs unit_price et purchase_date lors de la création
    $cryptoWallet = CryptoWallet::create([
        'idWallet' => $wallet->id,
        'idCrypto' => $cryptoId,
        'quantity' => $quantity,
        'status' => 'active',
        'unit_price' => $price, // Ajouter le prix unitaire
        'purchase_date' => now(), // Ajouter la date d'achat
    ]);
}

            // Ajouter une entrée dans CryptoBankStore
            $cryptoBankStore = CryptoBankStore::create([
                'idCryptoWallet' => $cryptoWallet->id,
                'quantity' => $quantity,
                'unitPrice' => $price,
                'totalPrice' => $totalPrice,
                'operationStatus' => 'completed',
                'operationDate' => now(),
            ]);

            // Débiter le solde du wallet
            $wallet->balance -= $totalPrice;
            $wallet->save();

            // Enregistrer la transaction
            Transaction::create([
                'idCryptoBankStore' => $cryptoBankStore->id,
                'idRecipient' => $user->id,
                'fee' => 0, // Ajoutez les frais si nécessaire
                'date' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Crypto purchase successful!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Purchase failed.', 'details' => $e->getMessage()], 500);
        }
    }
 public function getUserWallet()
{
    $user = Auth::user();

    // Récupérer les crypto-monnaies dans le portefeuille de l'utilisateur
    $wallet = Wallet::where('idUser', $user->id)->first();

    if (!$wallet) {
        return response()->json(['error' => 'Wallet not found.'], 404);
    }

    $cryptoWallets = CryptoWallet::where('idWallet', $wallet->id)
        ->with('cryptocurrency')  // Assurez-vous que CryptoWallet a une relation avec le modèle Cryptocurrency
        ->get();

    // Ajouter des informations d'achat à chaque crypto-monnaie du portefeuille
    $cryptoWalletDetails = $cryptoWallets->map(function ($cryptoWallet) {
        return [
            'image' => $cryptoWallet->cryptocurrency->image,
            'name' => $cryptoWallet->cryptocurrency->name,
            'quantity' => $cryptoWallet->quantity,
            'unitPrice' => $cryptoWallet->unit_price, // Assurez-vous que `unit_price` existe
            'purchaseDate' => $cryptoWallet->purchase_date, // Si disponible
            'totalPrice' => $cryptoWallet->quantity * $cryptoWallet->unit_price // Calcul du prix total
        ];
    });

    return response()->json($cryptoWalletDetails);
}

//get user transaction
public function getUserTransactions()
{
    $user = Auth::user();

    // Si l'utilisateur est un admin, récupérer toutes les transactions
    if ($user->role === 'admin') {
        $transactions = Transaction::with(['cryptoBankStore.cryptoWallet.cryptocurrency'])
            ->orderBy('date', 'desc')
            ->get();
    } else {
        // Si c'est un client, récupérer seulement ses transactions associées à son wallet
        $wallet = Wallet::where('idUser', $user->id)->first();

        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found.'], 404);
        }

        $transactions = Transaction::whereHas('cryptoBankStore.cryptoWallet', function ($query) use ($wallet) {
            $query->where('idWallet', $wallet->id);
        })
        ->with(['cryptoBankStore.cryptoWallet.cryptocurrency'])
        ->orderBy('date', 'desc')
        ->get();
    }

    // Mapper les transactions pour un affichage plus clair
    $formattedTransactions = $transactions->map(function ($transaction) use ($user) {
        return [
            'userId' => $user->id, // Ajout de l'ID de l'utilisateur
            'cryptoName' => $transaction->cryptoBankStore->cryptoWallet->cryptocurrency->name ?? 'Unknown',
            'cryptoImage' => $transaction->cryptoBankStore->cryptoWallet->cryptocurrency->image ?? null,
            'quantity' => $transaction->cryptoBankStore->quantity,
            'unitPrice' => $transaction->cryptoBankStore->unitPrice,
            'totalPrice' => $transaction->cryptoBankStore->totalPrice,
            'operationStatus' => $transaction->cryptoBankStore->operationStatus,
            'transactionDate' => $transaction->date,
        ];
    });

    return response()->json($formattedTransactions);
}




}

