<?php

namespace App\Http\Services;

use App\Models\Wallet;
use App\Models\CryptoWallet;
use App\Models\Transaction;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CryptoPurchaseService
{
    public function addCrypto($cryptoData)
    {

        DB::beginTransaction();
        try {
            // Vérification d'unicité
            $existingCrypto = Cryptocurrency::where('name', $cryptoData['name'])->first();
            if ($existingCrypto) {
                return ['error' => 'This cryptocurrency already exists.'];
            }

            // Validation des données
            if (empty($cryptoData['name'])) {
                return ['error' => 'Name is required.'];
            }

            if (!isset($cryptoData['currentPrice']) || $cryptoData['currentPrice'] <= 0) {
                return ['error' => 'Valid current price is required.'];
            }

            if (!isset($cryptoData['inStock']) || $cryptoData['inStock'] < 0) {
                return ['error' => 'Valid stock quantity is required.'];
            }

            // Gestion de l'image
            $imagePath = 'default_crypto.png';
            if (isset($cryptoData['image'])) {
                $imagePath = $this->handleImageUpload($cryptoData['image'], $cryptoData['name']);
            }

            // Création de la crypto
            $crypto = Cryptocurrency::create([
                'name' => trim($cryptoData['name']),
                'image' => $imagePath,
                'currentPrice' => $cryptoData['currentPrice'],
                'inStock' => $cryptoData['inStock']
            ]);

            DB::commit();
            return [
                'message' => 'Cryptocurrency added successfully!',
                'crypto' => $crypto
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => 'Add failed: ' . $e->getMessage()];
        }
    }

    private function handleImageUpload($image, $cryptoName)
    {
        if (!$image->isValid()) {
            throw new \Exception('Invalid image file.');
        }

        // Remplacer les espaces par des underscores
        $cleanedName = str_replace(' ', '_', $cryptoName);


        $filename = preg_replace('/[^a-zA-Z0-9_]/', '', $cleanedName) . '.' . $image->extension();
        $filename = strtolower($filename);

        // Stockage dans le dossier public
        $image->storeAs('cryptos', $filename, 'public');

        return $filename;
    }

    public function editCrypto($cryptoId, $cryptoData)
    {
        DB::beginTransaction();
        try {
            $crypto = Cryptocurrency::find($cryptoId);
            if (!$crypto) {
                return ['error' => 'Cryptocurrency not found.'];
            }

            // Gestion du nom
            if (isset($cryptoData['name'])) {
                $newName = trim($cryptoData['name']);
                if ($newName !== $crypto->name) {
                    $existing = Cryptocurrency::where('name', $newName)->first();
                    if ($existing) {
                        return ['error' => 'This cryptocurrency name already exists.'];
                    }
                    $crypto->name = $newName;
                }
            }

            // Gestion du prix
            if (isset($cryptoData['currentPrice'])) {
                if ($cryptoData['currentPrice'] <= 0) {
                    return ['error' => 'Valid current price is required.'];
                }
                $crypto->currentPrice = $cryptoData['currentPrice'];
            }

            // Gestion du stock
            if (isset($cryptoData['inStock'])) {
                if ($cryptoData['inStock'] < 0) {
                    return ['error' => 'Valid stock quantity is required.'];
                }
                $crypto->inStock = $cryptoData['inStock'];
            }

            // Gestion de l'image (toujours nommée crypto_name.png)
            if (isset($cryptoData['image'])) {
                // Supprimer l'ancienne image sauf si c'est l'image par défaut
                if ($crypto->image !== 'default_crypto.png') {
                    Storage::disk('public')->delete('cryptos/' . $crypto->image);
                }

                // Utiliser le nom mis à jour si nécessaire
                $nameForImage = $crypto->name ?? $cryptoData['name'];
                $crypto->image = $this->handleImageUpload($cryptoData['image'], $nameForImage);
            }

            $crypto->save();
            DB::commit();

            return [
                'message' => 'Cryptocurrency updated successfully!',
                'crypto' => $crypto
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => 'Update failed: ' . $e->getMessage()];
        }
    }


    public function buyCrypto($cryptoId, $quantity, $price)
    {
        if ($quantity <= 0 || $price <= 0) {
            return ['error' => 'Invalid quantity or price.'];
        }

        $user = Auth::user();
        $totalPrice = $quantity * $price;

        DB::beginTransaction();
        try {
            $wallet = Wallet::where('idUser', $user->id)->lockForUpdate()->first();
            if (!$wallet) {
                return ['error' => 'Wallet not found.'];
            }

            if ($wallet->balance < $totalPrice) {
                return ['error' => 'Insufficient balance.'];
            }

            $cryptoCurrency = Cryptocurrency::where('id', $cryptoId)->lockForUpdate()->first();
            if (!$cryptoCurrency || $cryptoCurrency->inStock < $quantity) {
                return ['error' => 'Insufficient stock for the requested quantity.'];
            }

            $userCryptoWallet = CryptoWallet::where('idWallet', $wallet->id)
                ->where('idCrypto', $cryptoId)
                ->lockForUpdate()
                ->first();

            if ($userCryptoWallet) {
                $userCryptoWallet->quantity += $quantity;
            } else {
                $userCryptoWallet = CryptoWallet::create([
                    'idWallet' => $wallet->id,
                    'idCrypto' => $cryptoId,
                    'quantity' => $quantity,
                ]);
            }

            $cryptoCurrency->inStock -= $quantity;
            $wallet->balance -= $totalPrice;

            $cryptoCurrency->save();
            $userCryptoWallet->save();
            $wallet->save();

            Transaction::create([
                'idCryptoWallet' => $userCryptoWallet->id,
                'quantity' => $quantity,
                'unitPrice' => $price,
                'totalPrice' => $totalPrice,
                'operationStatus' => 'completed',
                'date' => now(),
                'type' => 'buy',
            ]);

            DB::commit();
            return ['message' => 'Crypto purchase successful!'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => 'Purchase failed.', 'details' => $e->getMessage()];
        }
    }

    public function sellCrypto($cryptoId, $quantity, $price)
    {
        if ($quantity <= 0 || $price <= 0) {
            return ['error' => 'Invalid quantity or price.'];
        }

        $user = Auth::user();
        $totalPrice = $quantity * $price;

        DB::beginTransaction();
        try {
            $wallet = Wallet::where('idUser', $user->id)->lockForUpdate()->first();
            if (!$wallet) {
                return ['error' => 'Wallet not found.'];
            }

            $userCryptoWallet = CryptoWallet::where('idWallet', $wallet->id)
                ->where('idCrypto', $cryptoId)
                ->lockForUpdate()
                ->first();

            if (!$userCryptoWallet || $userCryptoWallet->quantity < $quantity) {
                return ['error' => 'Insufficient cryptocurrency quantity to sell.'];
            }

            $cryptoCurrency = Cryptocurrency::where('id', $cryptoId)->lockForUpdate()->first();
            if (!$cryptoCurrency) {
                return ['error' => 'Cryptocurrency not found.'];
            }

            $userCryptoWallet->quantity -= $quantity;
            if ($userCryptoWallet->quantity == 0) {
                $userCryptoWallet->delete();
            } else {
                $userCryptoWallet->save();
            }

            $cryptoCurrency->inStock += $quantity;
            $wallet->balance += $totalPrice;

            $cryptoCurrency->save();
            $wallet->save();

            Transaction::create([
                'idCryptoWallet' => $userCryptoWallet->id,
                'quantity' => $quantity,
                'unitPrice' => $price,
                'totalPrice' => $totalPrice,
                'operationStatus' => 'completed',
                'date' => now(),
                'type' => 'sell',
            ]);

            DB::commit();
            return ['message' => 'Crypto sale successful!'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => 'Sale failed.', 'details' => $e->getMessage()];
        }
    }

    public function getUserWallet()
    {
        $user = Auth::user();

        $wallet = Wallet::where('idUser', $user->id)->first();
        if (!$wallet) {
            return ['error' => 'Wallet not found.'];
        }

        $cryptoWallets = CryptoWallet::where('idWallet', $wallet->id)
            ->with('cryptocurrency')
            ->get();

        $cryptoWalletDetails = $cryptoWallets->map(function ($cryptoWallet) {
            $latestTransaction = $cryptoWallet->transactions()->latest('date')->first();

            return [
                'id' => $cryptoWallet->cryptocurrency->id,
                'image' => $cryptoWallet->cryptocurrency->image,
                'name' => $cryptoWallet->cryptocurrency->name,
                'quantity' => (float) $cryptoWallet->quantity,
                'unitPrice' => (float) ($latestTransaction->unitPrice ?? 0),
                'purchaseDate' => $latestTransaction->date ?? null,
                'totalPrice' => (float) ($cryptoWallet->quantity * ($latestTransaction->unitPrice ?? 0)),
            ];
        });

        return $cryptoWalletDetails;
    }

    public function getUserTransactions($role)
    {
        $user = Auth::user();

        if ($role === 'admin') {
            $transactions = Transaction::with(['cryptoWallet.cryptocurrency', 'cryptoWallet.wallet.user'])
                ->orderBy('date', 'desc')
                ->get();
        } else {
            $wallet = Wallet::where('idUser', $user->id)->first();
            if (!$wallet) {
                return ['error' => 'Wallet not found.'];
            }

            $transactions = Transaction::whereHas('cryptoWallet', function ($query) use ($wallet) {
                $query->where('idWallet', $wallet->id);
            })
                ->with(['cryptoWallet.cryptocurrency', 'cryptoWallet.wallet.user'])
                ->orderBy('date', 'desc')
                ->get();
        }

        return $transactions->map(function ($transaction) {
            return [
                'userId' => $transaction->cryptoWallet->wallet->user->id ?? null,
                'userName' => $transaction->cryptoWallet->wallet->user->name ?? 'Unknown',
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
    }

    public function getTotalCryptoPurchases()
    {
        $user = Auth::user();

        $wallet = Wallet::where('idUser', $user->id)->first();
        if (!$wallet) {
            return ['error' => 'Wallet not found.'];
        }

        $totalPurchases = Transaction::whereHas('cryptoWallet', function ($query) use ($wallet) {
            $query->where('idWallet', $wallet->id);
        })
            ->where('type', 'buy')
            ->sum('totalPrice');

        return ['totalCryptoPurchase' => (float) $totalPurchases];
    }
}
