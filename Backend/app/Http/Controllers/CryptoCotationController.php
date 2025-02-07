<?php

namespace App\Http\Controllers;

use App\Models\CryptoCotation;
use Illuminate\Http\Request;

class CryptoCotationController extends Controller
{
    public function index()
    {
      
        $cryptos = CryptoCotation::all()->map(function ($crypto) {
            return [
                'crypto_name' => $crypto->crypto_name,
                'current_value' => $crypto->value,
                'date' => $crypto->date,
                'image_url' => asset('storage/cryptos/' . strtolower(str_replace(' ', '_', $crypto->crypto_name)) . '.png')
            ];
        });

        return response()->json($cryptos);
    }
    
}
