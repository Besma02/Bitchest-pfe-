<?php

namespace App\Http\Controllers;

use App\Http\Services\StatsService;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Pour le client : Valeur totale du portefeuille
     */
    public function userPortfolio(Request $request)
    {
        $userId = $request->id;
        $data = $this->statsService->getUserPortfolio($userId);
        return response()->json($data);
    }

    /**
     * Pour le client : Détails par crypto
     */
    public function userCryptoDetails(Request $request)
    {
        $userId = $request->id;
        $data = $this->statsService->getUserCryptoDetails($userId);
        return response()->json($data);
    }

    /**
     * Pour l'admin : Valeur totale des cryptos sur la plateforme
     */
    public function platformTotalValue()
    {
        $data = $this->statsService->getPlatformTotalValue();
        return response()->json($data);
    }

    /**
     * Pour l'admin : Valeur par crypto sur la plateforme
     */
    public function platformCryptoDetails()
    {
        $data = $this->statsService->getPlatformCryptoDetails();
        return response()->json($data);
    }

    /**
     * Pour l'admin : Top 5 des cryptos les plus échangées
     */
    public function topCryptos()
    {
        $data = $this->statsService->getTopCryptos();
        return response()->json($data);
    }
}
