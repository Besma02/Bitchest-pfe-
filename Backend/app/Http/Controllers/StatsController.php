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

    public function getStats(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $role = $user->role;

        if ($role === 'admin') {
            $adminStats = $this->statsService->getAdminStats();
            return response()->json([
                'userId' => $userId,
                'totalValue' => $adminStats['totalValue'],
                'totalByCrypto' => $adminStats['totalByCrypto'],
                'totalPurchases' => $adminStats['totalPurchases']
            ]);
        } else {
            $userStats = $this->statsService->getUserStats($userId, $user->balance);
            return response()->json([
                'userId' => $userId,
                'balance' => $userStats['balance'],
                'investedAmount' => $userStats['investedAmount'],
                'currentValueByCrypto' => $userStats['currentValueByCrypto']
            ]);
        }
    }
}
