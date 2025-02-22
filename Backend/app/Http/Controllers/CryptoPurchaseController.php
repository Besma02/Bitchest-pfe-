<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\CryptoWallet;
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
                $cryptoWallet = CryptoWallet::create([
                    'idWallet' => $wallet->id,
                    'idCrypto' => $cryptoId,
                    'quantity' => $quantity,
                    'status' => 'active',
                ]);
            }

            // Débiter le solde du wallet
            $wallet->balance -= $totalPrice;
            $wallet->save();

            // Enregistrer la transaction
            Transaction::create([
                'idCryptoWallet' => $cryptoWallet->id,
                'quantity' => $quantity,
                'unitPrice' => $price,
                'totalPrice' => $totalPrice,
                'operationStatus' => 'completed',
                'date' => now(),
                'type' => 'buy',
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

        // Récupérer le wallet de l'utilisateur
        $wallet = Wallet::where('idUser', $user->id)->first();
        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found.'], 404);
        }

        // Récupérer les crypto-monnaies détenues par l'utilisateur
        $cryptoWallets = CryptoWallet::where('idWallet', $wallet->id)
            ->with('cryptocurrency')
            ->get();

      // Ajouter des informations d'achat basées sur la dernière transaction
$cryptoWalletDetails = $cryptoWallets->map(function ($cryptoWallet) {
    $latestTransaction = $cryptoWallet->transactions()->latest('date')->first();

    return [
        'id' => $cryptoWallet->cryptocurrency->id,  // Ajouter l'ID de la crypto
        'image' => $cryptoWallet->cryptocurrency->image,
        'name' => $cryptoWallet->cryptocurrency->name,
        'quantity' => (float) $cryptoWallet->quantity, // Conversion en nombre
        'unitPrice' => (float) ($latestTransaction->unitPrice ?? 0), // Conversion en nombre
        'purchaseDate' => $latestTransaction->date ?? null,
        'totalPrice' => (float) ($cryptoWallet->quantity * ($latestTransaction->unitPrice ?? 0)), // Conversion en nombre
    ];
});

        return response()->json($cryptoWalletDetails);
    }

    public function getUserTransactions()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $transactions = Transaction::with(['cryptoWallet.cryptocurrency', 'cryptoWallet.wallet.user'])
                ->orderBy('date', 'desc')
                ->get();
        } else {
            $wallet = Wallet::where('idUser', $user->id)->first();
            if (!$wallet) {
                return response()->json(['error' => 'Wallet not found.'], 404);
            }

            $transactions = Transaction::whereHas('cryptoWallet', function ($query) use ($wallet) {
                $query->where('idWallet', $wallet->id);
            })
                ->with(['cryptoWallet.cryptocurrency', 'cryptoWallet.wallet.user'])
                ->orderBy('date', 'desc')
                ->get();
        }

        $formattedTransactions = $transactions->map(function ($transaction) {
            return [
                'userId' => $transaction->cryptoWallet->wallet->user->id ?? null,
                'userName' => $transaction->cryptoWallet->wallet->user->name ?? 'Inconnu',
                'cryptoName' => $transaction->cryptoWallet->cryptocurrency->name ?? 'Unknown',
                'cryptoImage' => $transaction->cryptoWallet->cryptocurrency->image ?? null,
                'quantity' => $transaction->quantity,
                'unitPrice' => $transaction->unitPrice,
                'totalPrice' => $transaction->totalPrice,
                'operationStatus' => $transaction->operationStatus,
                'transactionDate' => $transaction->date,
                'type' => $transaction->type,
               
            ];
        });

        return response()->json($formattedTransactions);
    }
}
