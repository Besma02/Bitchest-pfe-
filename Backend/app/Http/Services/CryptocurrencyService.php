<?php
namespace App\Http\Services;

use App\Models\Cryptocurrency;
use Illuminate\Validation\ValidationException;

class CryptocurrencyService
{
    public function addCrypto(array $data)
    {
        return Cryptocurrency::create($data);
    }

    public function updateCrypto($id, array $data)
    {
        $crypto = Cryptocurrency::findOrFail($id);
        $crypto->update($data);
        return $crypto;
    }

    public function validateCryptoData(array $data, $id = null)
    {
        $rules = [
            'name' => 'required|string|unique:cryptocurrencies,name' . ($id ? ',' . $id : ''),
            'logo' => 'required|string',
            'current_price' => 'required|numeric|min:0',
        ];

        return validator($data, $rules)->validate();
    }
}
